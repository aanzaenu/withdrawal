<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->uri = 'dashboard';
        $this->title = 'Dashboard';
    }
    public function index()
    {
        if(is_admin() || is_subadmin() || is_cs() || is_wd())
        {
            $data['title'] = $this->title." - ".env('APP_NAME', 'Awesome Website');
            $data['pagetitle'] = $this->title;
            $data['banks'] = Bank::orderBy('name', 'ASC')->get();
            return view('backend.'.$this->uri, $data);
        }else{
            abort(404);
        }
    }
}
