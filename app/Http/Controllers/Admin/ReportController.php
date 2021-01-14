<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Bank;
use App\Withdrawal;
use App\Exports\WithdrawalExport;
use Maatwebsite\Excel\Facades\Excel;
date_default_timezone_set('Asia/Jakarta');

class ReportController extends Controller
{
    public function __construct()
    {
        $this->uri = 'reports';
        $this->title = 'Reports';
        $this->role = [1, 3, 4];
    }
    public function index()
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            $data['operators'] = User::with('roles')->whereHas('roles', function($query){
                                    $query->where('roles.id', 3);
                                })->orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.form', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            if(!empty($request->get('orderby')) || !empty($request->get('operator')) || !empty($request->get('banks')) || !empty($request->get('from')) || !empty($request->get('to')) || $request->get('status') == 0 || !empty($request->get('status')))
            {
                $model = Withdrawal::with('banks');
                if($request->get('banks'))
                {
                    $rol = $request->get('banks');
                    $model->whereHas('banks', function($query) use ($rol){
                        $query->where('banks.id', $rol);
                    });
                }
                if(!empty($request->get('status')) || $request->get('status') == 0)
                {
                    if($request->get('status') !== 'all')
                    {
                        $model->where('status', $request->get('status'));
                    }
                }
                if(!empty($request->get('operator')))
                {
                    $model->where('operator', $request->get('operator'));
                }
                if(!empty($request->get('from')) && !empty($request->get('to')))
                {
                    $from = $request->get('from').' 00:00:01';
                    $to = $request->get('to').' 23:23:59';
                    $d_from = strtotime($from);
                    $d_to = strtotime($to);
                    $sfrom = date('Y-m-d H:i:s', $d_from);
                    $sto = date('Y-m-d H:i:s', $d_to);

                    $model->where('time', '>=', $sfrom);
                    $model->where('time', '<=', $sto);
                }
                if($request->get('orderby'))
                {
                    if($request->get('order') == 'asc')
                    {
                        $model->orderBy($request->get('orderby'), 'ASC');
                    }else{
                        $model->orderBy($request->get('orderby'), 'DESC');
                    }
                }else{
                    $model->orderBy('id', 'DESC');
                }

                $data['title'] = "Hasil ".$this->title." - ".env('APP_NAME', 'Awesome Website');
                $data['pagetitle'] = "Hasil ".$this->title;
                $data['uri'] = $this->uri;
                $data['lists'] = $model->paginate(20);
                $data['banks'] = Bank::orderBy('name', 'ASC')->get();
                return view('backend.'.$this->uri.'.list', $data);
            }else{
                return redirect()->route('admin.'.$this->uri.'.index');
            }
        }else{
            abort(404);
        }
    }
    public function download(Request $request)
    {
        return Excel::download(new WithdrawalExport($request->from, $request->to, $request->operator, $request->banks, $request->status), 'report.xlsx');
    }
}
