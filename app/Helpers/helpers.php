<?php
use Illuminate\Support\Facades\Auth;
use App\Withdrawal;
use App\Amount;
function is_admin()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('Admin'))
        {
            return true;
        }
    }
    return false;
}
function is_subadmin()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('Subadmin'))
        {
            return true;
        }
    }
    return false;
}
function is_cs()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('CS'))
        {
            return true;
        }
    }
    return false;
}
function is_wd()
{
    if(Auth::check())
    {
        if(Auth::user()->hasRole('Tim Withdraw'))
        {
            return true;
        }
    }
    return false;
}
function is_login()
{
    if(Auth::check())
    {
        return true;
    }
    return false;
}
function asset_url($string)
{
    return env('APP_URL').$string;
}
function count_withdrawal()
{
    $model = Withdrawal::where('status', 0)->count();
    if($model)
    {
        return $model;
    }
    return 0;
}
function count_amount()
{
    $model = Amount::where('status', 0)->count();
    if($model)
    {
        return $model;
    }
    return 0;
}