<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>{{$title ?? ''}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Powerfull dashboard Admin" name="description" />
    <meta content="aanzr.io" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset('backend/css/custom.css')}} " rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/animate.css/animate.css.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/jquery-toast-plugin/jquery-toast-plugin.min.css')}}" rel="stylesheet" type="text/css" />
    @yield('css')
    <link href="{{asset('backend/css/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <style>
        .container-fluid{
            max-width: 100%!important;
        }
        .topnav .navbar-nav .nav-item:first-of-type .nav-link{
            padding: calc(33px / 2) 1.1rem;
        }
        .topnav .navbar-nav .nav-link:focus, .topnav .navbar-nav .nav-link:hover{
            background-color: #437512;
            opacity: .5;
        }
        .topnav .navbar-nav .nav-link{
            border-right: 1px solid #FFFFFF;
        }
        .navbar-light .navbar-nav .active > .nav-link, .navbar-light .navbar-nav .nav-link.active{
            background-color: #323a46!important;
        }
    </style>
</head>