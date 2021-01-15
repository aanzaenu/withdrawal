<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Bank;
use App\User;
use App\Amount;
use Illuminate\Support\Facades\File;
date_default_timezone_set('Asia/Jakarta');

class AmountController extends Controller
{
    public function __construct()
    {
        $this->uri = 'amounts';
        $this->title = 'Suntik Dana';
    }
    public function index()
    {
        if(is_admin() || is_subadmin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = Amount::orderBy('id', 'DESC')->paginate(20);
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            $data['users'] = User::whereHas('roles', function($query){
                                    $query->whereIn('roles.id', [1, 4]);
                                })->orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')) || !empty($request->get('banks')) || !empty($request->get('users')))
            {
                $model = Amount::where('id', '>', 0);
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('nominal', 'like', '%'.strip_tags($request->get('query')).'%');
                    });
                }
                if(!empty($request->get('banks')))
                {
                    $model->where('bank_id', $request->get('banks'));
                }
                if(!empty($request->get('users')))
                {
                    $model->where('user_id', $request->get('users'));
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

                $data['title'] = "Pencarian ".$this->title." - ".env('APP_NAME', 'Awesome Website');
                $data['pagetitle'] = "Pencarian ".$this->title;
                $data['uri'] = $this->uri;
                $data['lists'] = $model->paginate(20);
                $data['banks'] = Bank::orderBy('name', 'ASC')->get();
                $data['users'] = User::whereHas('roles', function($query){
                                        $query->whereIn('roles.id', [1, 4]);
                                    })->orderBy('name', 'ASC')->get();
                return view('backend.'.$this->uri.'.list', $data);
            }else{
                return redirect()->route('admin.'.$this->uri.'.index');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, Amount $amount)
    {
        if(is_admin() || is_subadmin())
        {
            $amount->delete();
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function deletemass(Request $request)
    {
        
        if(is_admin() || is_subadmin())
        {
            $id = explode(",", $request->ids);            
            $amounts = Amount::find($id);
            foreach($amounts as $key=> $amount)
            {
                $amount->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function apdet(Request $request)
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            $model = Amount::find($request->id);
            if(!$model)
            {
                $request->session()->flash('error', 'Data tidak ditemukan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }
            $bank = Bank::find($model->bank_id);
            if(!$bank)
            {
                $request->session()->flash('error', 'Bank tidak ditemukan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }
            $saldo_bank = $bank->saldo;
            $nominal = $model->nominal;
            $model->status = $request->status;
            if($request->status == 1)
            {
                $bank->saldo = $saldo_bank+$nominal;
                $bank->save();
            }
            $save = $model->save();
            if($save)
            {
                $request->session()->flash('success', 'Data Updated');
                return redirect()->route('admin.'.$this->uri.'.index');
            }
            $request->session()->flash('error', 'Error Update');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
