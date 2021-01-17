<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use App\Bank;
use App\Amount;
use Illuminate\Support\Facades\File;
use App\Events\NotificationPusherEvent;
date_default_timezone_set('Asia/Jakarta');

class BankController extends Controller
{
    public function __construct()
    {
        $this->uri = 'banks';
        $this->title = 'Banks';
    }
    public function index()
    {
        if(is_admin() || is_subadmin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            $data['lists'] = Bank::orderBy('name', 'ASC')->paginate(20);
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')))
            {
                $model = Bank::where('id', '>', 0);
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('bankname', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('rec', 'like', '%'.strip_tags($request->get('query')).'%');
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
                    $model->orderBy('name', 'ASC');
                }

                $data['title'] = "Pencarian ".$this->title." - ".env('APP_NAME', 'Awesome Website');
                $data['pagetitle'] = "Pencarian ".$this->title;
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
    public function create()
    {
        if(is_admin() || is_subadmin())
        {
            $data['title'] = "Tambah ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Tambah ".$this->title;
            $data['uri'] = $this->uri;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, Bank $bank)
    {
        if(is_admin() || is_subadmin())
        {
            $requestfile = '';
            if($request->file)
            {
                $requestfile = 'mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:2046';
            }
            $validasi =[
                    'name' => ['required'],
                    'bankname' => ['required'],
                    'rec' => ['required'],
                    'saldo' => ['required'],
                    'file' => [$requestfile],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'bankname.required' => 'Atas Nama Bank tidak boleh kosong',
                'rec.required' => 'Nomor Rekening Bank tidak boleh kosong',
                'saldo.required' => 'Saldo Bank tidak boleh kosong',
                'file.mimes' => 'File tidak didukung',
                'file.max' => 'Ukuran maksimal 2Mb',
            ];

            $request->validate($validasi, $msg);
            $bank->name = trim($request->name);
            $bank->bankname = trim($request->bankname);
            $bank->rec = trim($request->rec);
            $bank->saldo = Str::slug(trim($request->saldo), '');
            if($request->file)
            {
                $filed = $request->file;
                $path = 'assets/images/banks/';
                $dir = public_path($path);
                if(!File::isDirectory($dir))
                {
                    File::makeDirectory($dir, 0777, true, true);
                }
                $file_name = Str::slug($filed->getClientOriginalName(), '-').'-'.time();
                $name = $file_name.'.'.$filed->getClientOriginalExtension();
                $filed->move($dir,$name);
                
                $bank->image = $path.$name;
            }
    
            if($bank->save())
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
    public function edit(Bank $bank)
    {
        if(is_admin() || is_subadmin())
        {
            $data['row'] = $bank;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, Bank $bank)
    {
        if(is_admin() || is_subadmin())
        {
            $requestfile = '';
            if($request->file)
            {
                $requestfile = 'mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:2046';
            }
            $validasi =[
                    'name' => ['required'],
                    'bankname' => ['required'],
                    'rec' => ['required'],
                    'saldo' => ['required'],
                    'file' => [$requestfile],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'bankname.required' => 'Atas Nama Bank tidak boleh kosong',
                'rec.required' => 'Nomor Rekening Bank tidak boleh kosong',
                'saldo.required' => 'Saldo Bank tidak boleh kosong',
                'file.mimes' => 'File tidak didukung',
                'file.max' => 'Ukuran maksimal 2Mb',
            ];
            $request->validate($validasi, $msg);
            
            $bank->name = trim($request->name);
            $bank->bankname = trim($request->bankname);
            $bank->rec = trim($request->rec);
            $bank->saldo = Str::slug(trim($request->saldo), '');
            if($request->file)
            {
                $filed = $request->file;
                $path = 'assets/images/banks/';
                $dir = public_path($path);
                if(!File::isDirectory($dir))
                {
                    File::makeDirectory($dir, 0777, true, true);
                }
                $file_name = Str::slug($filed->getClientOriginalName(), '-').'-'.time();
                $name = $file_name.'.'.$filed->getClientOriginalExtension();
                $filed->move($dir,$name);
                
                $bank->image = $path.$name;
            }
            
            if($bank->save())
            {
                $request->session()->flash('success', 'Sukses update '.$this->title);
                if(is_cs())
                {
                    return redirect()->route('admin.home');
                }
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat update '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, Bank $bank)
    {
        if(is_admin() || is_subadmin())
        {
            if($bank->path)
            {
                if(File::exists(public_path($bank->path)))
                {
                    File::delete(public_path($bank->path));
                }
            }
            $bank->delete();
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
            $banks = Bank::find($id);
            foreach($banks as $key=> $bank)
            {
                if($bank->path)
                {
                    if(File::exists(public_path($bank->path)))
                    {
                        File::delete(public_path($bank->path));
                    }
                }
                $bank->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
    public function suntikdana(Request $request)
    {
        
        if(is_admin() || is_subadmin() ||  is_wd())
        {
            $validasi =[
                'banks' => ['required'],
                'saldo' => ['required'],
                ];
            $msg = [
                'banks.required' => 'Bank tidak boleh kosong',
                'saldo.required' => 'Saldo Bank tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);
            $model = Amount::create([
                'bank_id' => $request->banks,
                'nominal' => Str::slug(trim($request->saldo), ''),
                'user_id' => Auth::user()->id
            ]);
            if($model)
            {
                event(new NotificationPusherEvent('amount', Auth::user()->name, 'hello world!!!'));
                $request->session()->flash('success', 'Permintaan Suntik Dana terkirim.');
            }else{
                $request->session()->flash('error', 'Error saat memproses data');
            }
            return redirect()->back();
        }else{
            abort(404);
        }
    }
    public function suntikdanabak(Request $request)
    {
        
        if(is_admin() || is_subadmin())
        {         
            $bank = Bank::find($request->banks);
            if(!$bank)
            {
                $request->session()->flash('error', 'Not Found!');
                return redirect()->back();
            }
            
            $validasi =[
                'saldo' => ['required'],
                ];
            $msg = [
                'saldo.required' => 'Saldo Bank tidak boleh kosong',
            ];
            $request->validate($validasi, $msg);
            $saldo = Str::slug(trim($request->saldo), '');
            $bank->saldo = $bank->saldo + intval($saldo);
            if($bank->save())
            {
                $request->session()->flash('success', 'Sukses update '.$this->title);

            }else{
                $request->session()->flash('error', 'Error!');
            }
            return redirect()->back();
        }else{
            abort(404);
        }
    }
}
