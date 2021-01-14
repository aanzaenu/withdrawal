<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Bank;
use App\Withdrawal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
date_default_timezone_set('Asia/Jakarta');

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->uri = 'withdrawals';
        $this->title = 'Withdrawal';
        $this->role = [1, 3, 4];
    }
    public function index()
    {
        if(is_admin() || is_subadmin() || is_cs() || is_wd())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            $data['operators'] = User::with('roles')->whereHas('roles', function($query){
                                    $query->where('roles.id', 3);
                                })->orderBy('name', 'ASC')->get();
            if(is_admin() || is_subadmin() || is_wd())
            {
                $data['lists'] = Withdrawal::with('banks')->orderBy('id', 'DESC')->paginate(20);
            }else{
                $data['lists'] = Withdrawal::with('banks')->where('operator', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
            }
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin() || is_cs() || is_wd())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')) || !empty($request->get('operator')) || !empty($request->get('bank')))
            {
                $model = Withdrawal::with('banks');
                if($request->get('bank'))
                {
                    $rol = $request->get('bank');
                    $model->whereHas('banks', function($query) use ($rol){
                        $query->where('banks.id', $rol);
                    });
                }
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('bank', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('bankname', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('bankrec', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('nominal', 'like', '%'.strip_tags($request->get('query')).'%');
                    });
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
                $data['banks'] = Bank::orderBy('name', 'ASC')->get();
                $data['operators'] = User::with('roles')->whereHas('roles', function($query){
                                        $query->where('roles.id', 3);
                                    })->orderBy('name', 'ASC')->get();
                if(is_admin() || is_subadmin() || is_wd())
                {
                    $data['lists'] = $model->paginate(20);
                }else{
                    $data['lists'] = $model->where('operator', Auth::user()->id)->paginate(20);
                }
                return view('backend.'.$this->uri.'.list', $data);
            }else{
                return redirect()->route('admin.'.$this->uri.'.index');
            }
        }else{
            abort(404);
        }
    }
    public function create()
    {
        if(is_admin() || is_subadmin() || is_cs())
        {
            $data['title'] = "Form ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Form ".$this->title;
            $data['uri'] = $this->uri;
            $data['operators'] = User::with('roles')->whereHas('roles', function($query){
                                    $query->where('roles.id', 3);
                                })->orderBy('name', 'ASC')->get();
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, Withdrawal $withdrawal)
    {
        if(is_admin() || is_subadmin() || is_cs())
        {
            $oprator = '';
            $getoperator = Auth::user()->id;
            if(is_admin() || is_subadmin())
            {
                $oprator = 'required';
                $getoperator = $request->operator;
            }
            $validasi =[
                    'bank' => ['required'],
                    'bankname' => ['required'],
                    'bankrec' => ['required'],
                    'nominal' => ['required'],
                    'operator' => [$oprator],
                ];
            $msg = [
                'bank.required' => 'Bank User tidak boleh kosong',
                'bankname.required' => 'Atas Nama Bank User tidak boleh kosong',
                'bankrec.required' => 'No. Rekening Bank User tidak boleh kosong',
                'nominal.required' => 'Nominal tidak boleh kosong',
                'operator.required' => 'Operator tidak boleh kosong',
            ];

            $request->validate($validasi, $msg);

            $withdrawal->bank = trim($request->bank);
            $withdrawal->bankname = trim($request->bankname);
            $withdrawal->bankrec = trim($request->bankrec);
            $withdrawal->nominal = Str::slug(trim($request->nominal), '');
            $withdrawal->operator = trim($getoperator);
    
            if($withdrawal->save())
            {
                $request->session()->flash('success', $this->title.' baru ditambahkan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat menambah '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function edit(Withdrawal $withdrawal)
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            $data['row'] = $withdrawal;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            if(is_admin() || is_subadmin())
            {
                $data['operators'] = User::with('roles')->whereHas('roles', function($query){
                                        $query->where('roles.id', 3);
                                    })->orderBy('name', 'ASC')->get();
            }else{
                $data['operators'] = User::where('id', $withdrawal->operator)->get();
            }
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Withdrawal $withdrawal)
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            $oprator = '';
            if(is_admin() || is_subadmin())
            {
                $oprator = 'required';
            }
            $validasi =[
                    'bank' => ['required'],
                    'bankname' => ['required'],
                    'bankrec' => ['required'],
                    'nominal' => ['required'],
                    'operator' => [$oprator],
                    'banks' => ['required'],
                    'time' => ['required'],
                ];
            $msg = [
                'bank.required' => 'Bank User tidak boleh kosong',
                'bankname.required' => 'Atas Nama Bank User tidak boleh kosong',
                'bankrec.required' => 'No. Rekening Bank User tidak boleh kosong',
                'nominal.required' => 'Nominal tidak boleh kosong',
                'operator.required' => 'Operator tidak boleh kosong',
                'banks.required' => 'Withdrawal Bank tidak boleh kosong',
                'time.required' => 'Waktu Transfer tidak boleh kosong',
            ];
            
            $request->validate($validasi, $msg);
            
            $withdrawal->bank = trim($request->bank);
            $withdrawal->bankname = trim($request->bankname);
            $withdrawal->bankrec = trim($request->bankrec);
            $withdrawal->nominal = Str::slug(trim($request->nominal), '');
            $withdrawal->operator = trim($request->operator);
            $withdrawal->status = trim($request->status);
            $waktu = Carbon::parse($request->time.':00')->format('Y-m-d H:i:s');
            $withdrawal->time = $waktu;
    
            if($withdrawal->save())
            {
                if($withdrawal->banks()->first()){
                    $withdrawal->banks()->sync($request->banks);
                }else{
                    $withdrawal->banks()->attach($request->banks);
                }
                $request->session()->flash('success', 'Sukses update '.$this->title);
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat update '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, Withdrawal $withdrawal)
    {
        if(is_admin() || is_subadmin())
        {
            
            $number = $withdrawal->banks()->first();
            if($number)
            {
                $withdrawal->banks()->detach();
            }

            $withdrawal->delete();
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            $request->session()->flash('error', 'Akses ditolak');
            return redirect()->route('admin.'.$this->uri.'.index');
        }
    }
    public function deletemass(Request $request)
    {
        
        if(is_admin() || is_subadmin())
        {
            $id = explode(",", $request->ids);            
            $withdrawals = Withdrawal::find($id);
            foreach($withdrawals as $key=> $withdrawal)
            {
                $number = $withdrawal->banks()->first();
                if($number)
                {
                    $withdrawal->banks()->detach();
                }

                $withdrawal->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            $request->session()->flash('error', 'Akses ditolak');
            return redirect()->route('admin.'.$this->uri.'.index');
        }
    }
    public function apdet(Request $request)
    {
        if(is_admin() || is_subadmin() || is_wd())
        {
            $model = Withdrawal::find($request->id);
            if($request->banks)
            {
                $bank = Bank::where('id', $request->banks)->first();
                if($bank)
                {
                    $saldo = $bank->saldo;
                    if($saldo < $model->nominal)
                    {
                        $request->session()->flash('error', 'Saldo tidak mencukupi');
                        return redirect()->route('admin.'.$this->uri.'.index');
                    }
                }
            }
            $model->status = trim($request->status);
            $model->time = date('Y-m-d H:i:s', time());

            $save = $model->save();
            if($save)
            {
                if($request->banks)
                {
                    if($model->banks()->first()){
                        $model->banks()->sync($request->banks);
                    }else{
                        $model->banks()->attach($request->banks);
                    }
                    $bank = Bank::where('id', $request->banks)->first();
                    if($model->status == 1)
                    {
                        if($bank)
                        {
                            $saldo = $bank->saldo - $model->nominal;
                            $bank->saldo = $saldo;
                            $bank->save();
                        }
                    }
                }
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
