<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
date_default_timezone_set('Asia/Jakarta');

class UserController extends Controller
{    
    public function __construct()
    {
        $this->uri = 'users';
        $this->title = 'User';
        $this->role = [1, 3, 4];
    }
    public function index()
    {
        if(is_admin() || is_subadmin())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['uri'] = $this->uri;
            if(is_admin())
            {
                $data['roles'] = Role::whereIn('id', $this->role)->orderBy('name', 'ASC')->get();
                $data['lists'] = User::with('roles')->whereHas('roles', function($query){
                    $query->whereIn('roles.id', $this->role);
                })->orderBy('id', 'DESC')->paginate(20);
            }else{
                $data['lists'] = User::with('roles')->whereHas('roles', function($query){
                    $query->where('roles.id', 3);
                })->where('owner', Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
                $data['roles'] = Role::where('id', 3)->orderBy('name', 'ASC')->get();
            }
            return view('backend.'.$this->uri.'.list', $data);
        }else{
            abort(404);
        }
    }
    public function search(Request $request)
    {
        if(is_admin() || is_subadmin())
        {
            if(!empty($request->get('query')) || !empty($request->get('orderby')) || !empty($request->get('role')))
            {
                $model = User::with('roles');
                if($request->get('role'))
                {
                    $rol = $request->get('role');
                    $model->whereHas('roles', function($query) use ($rol){
                        $query->where('roles.id', $rol);
                    });
                }else{
                    if(is_admin()){
                        $model->whereHas('roles', function($query){
                            $query->whereIn('roles.id', $this->role);
                        });
                    }else{
                        $model->whereHas('roles', function($query){
                            $query->where('roles.id', 3);
                        });
                    }
                }
                if(!empty($request->get('query')))
                {
                    $model->where(function($query) use ($request){
                        return $query->where('username', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('name', 'like', '%'.strip_tags($request->get('query')).'%')
                                    ->orWhere('email', 'like', '%'.strip_tags($request->get('query')).'%');
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
                if(is_admin()){
                    $data['roles'] = Role::whereIn('id', $this->role)->orderBy('name', 'ASC')->get();
                    $data['lists'] = $model->paginate(20);
                }else{
                    $data['roles'] = Role::where('id', 3)->orderBy('name', 'ASC')->get();
                    $data['lists'] = $model->where('owner', Auth::user()->id)->paginate(20);
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
        if(is_admin() || is_subadmin())
        {
            $data['title'] = "Tambah ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Tambah ".$this->title;
            $data['uri'] = $this->uri;
            if(is_admin()){
                $data['roles'] = Role::whereIn('id', $this->role)->orderBy('name', 'ASC')->get();
            }else{
                $data['roles'] = Role::where('id', 3)->orderBy('name', 'ASC')->get();
            }
            return view('backend.'.$this->uri.'.create', $data);
        }else{
            abort(404);
        }
    }
    public function store(Request $request, User $user)
    {
        if(is_admin() || is_subadmin())
        {
            $validasi =[
                    'username' => ['required','unique:users'],
                    'name' => ['required'],
                    'email' => ['required','unique:users'],
                    'role' => ['required'],
                    'password' => ['required', 'min:8'],
                    'confirm_password' => ['required', 'same:password'],
                ];
            $msg = [
                'name.required' => 'Nama tidak boleh kosong',
                'username.required' => 'Nama tidak boleh kosong',
                'username.unique' => 'Nama sudah terdaftar',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Eama sudah terdaftar',
                'role.required' => 'Group harus dipilih',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 8 karakter',
                'confirm_password.required' => 'Konfirmasi Password tidak boleh kosong',
                'confirm_password.same' => 'Password tidak sama',
            ];

            $request->validate($validasi, $msg);
            $user->name = trim($request->name);
            $user->username = Str::slug(trim($request->username), '.');
            $user->email = trim($request->email);
            $user->password = Hash::make($request->input('password'));
    
            if($user->save())
            {
                $user->roles()->attach($request->role);
                $request->session()->flash('success', $this->title.' baru ditambahkan');
                return redirect()->route('admin.'.$this->uri.'.index');
            }else{
                $request->session()->flash('error', 'Error saat menambah '.$this->title.'!');
            }
        }else{
            abort(404);
        }
    }
    public function edit(User $user)
    {
        if($user->id == Auth::user()->id)
        {
            return redirect()->route('admin.users.profile');
        }
        if(is_admin() || is_subadmin())
        {
            $data['row'] = $user;
            $data['title'] = "Edit ".$this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Edit ".$this->title;
            $data['uri'] = $this->uri;
            if(is_admin()){
                $data['roles'] = Role::whereIn('id', $this->role)->orderBy('name', 'ASC')->get();
            }else{
                $data['roles'] = Role::where('id', 3)->orderBy('name', 'ASC')->get();
            }
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function update(Request $request, User $user)
    {
        if(is_admin() || is_subadmin() || is_cs()  || is_wd())
        {
            $validasi =[
                    'email' => ['required','unique:users,email,'.$user->id.',id'],
                    'name' => ['required'],
                    'role' => ['required'],
                ];
            $msg = [
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah terdaftar',
                'name.required' => 'Nama tidak boleh kosong',
                'role.required' => 'Group harus dipilih',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 8 karakter',
                'confirm_password.required' => 'Konfirmasi Password tidak boleh kosong',
                'confirm_password.same' => 'Password tidak sama',
            ];
            if($request->input('password'))
            {
                $user->password = Hash::make($request->input('password'));
            }
            $request->validate($validasi, $msg);

            $user->name = trim($request->name);
            $user->email = trim($request->email);
    
            if($user->save())
            {
                $user->roles()->sync($request->role);
                $request->session()->flash('success', 'Sukses update '.$this->title);
                if(is_cs() || is_wd())
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
    public function profile(Request $request)
    {
        if(is_admin() || is_subadmin() || is_cs() || is_wd())
        {
            $data['row'] = User::find(Auth::user()->id);
            $data['title'] = "Profile ".Auth::user()->name." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = "Profile ".Auth::user()->name;
            $data['uri'] = $this->uri;
            if(is_admin() || is_subadmin()){
                $data['roles'] = Role::orderBy('name', 'ASC')->get();
            }else{
                $data['roles'] = Role::whereIn('id', [3, 4])->orderBy('name', 'ASC')->get();
            }
            return view('backend.'.$this->uri.'.edit', $data);
        }else{
            abort(404);
        }
    }
    public function destroy(Request $request, User $user)
    {
        if($user->id == 1)
        {
            $request->session()->flash('error', 'Admin utama tidak boleh dihapus');
            return redirect()->route('admin.'.$this->uri.'.index');
        }
        if(is_admin() || is_subadmin())
        {
            
            $number = $user->roles()->get();
            if(count($number) > 0)
            {
                $user->roles()->detach();
            }

            $user->delete();
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
            $users = User::find($id);
            foreach($users as $key=> $user)
            {
                if($user->id == 1)
                {
                    $request->session()->flash('error', 'Admin utama tidak boleh dihapus');
                    return redirect()->route('admin.'.$this->uri.'.index');
                }
                $number = $user->roles()->get();
                if(count($number) > 0)
                {
                    $user->roles()->detach();
                }

                $user->delete();
            }
            $request->session()->flash('success', $this->title.' dihapus!');
            return redirect()->route('admin.'.$this->uri.'.index');
        }else{
            abort(404);
        }
    }
}
