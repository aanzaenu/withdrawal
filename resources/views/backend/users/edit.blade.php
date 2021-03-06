@extends('backend.layout.app')
@section('css')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ env('APP_NAME', 'Admin') }}</a></li>
                            <li class="breadcrumb-item active">{{ $pagetitle }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $pagetitle }}</h4>
                </div>
            </div>
        </div>
        @include('backend.layout.allert')
        <form method="POST" action="{{ route('admin.'.$uri.'.update', $row) }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-6">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif" id="username"  name="username"  placeholder="username" value="{{ $row->username }}" readonly disabled>                                    
                                    @error('username')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name"  name="name"  placeholder="Steve Job" value="{{ $row->name }}">                                    
                                    @error('name')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" id="email"  name="email"  placeholder="admin@email.com" value="{{ $row->email }}">                                    
                                    @error('email')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group {{ !is_admin() ? 'd-none' : '' }}">
                                    <label for="role">Group</label>
                                    <select id="role" name="role" class="form-control @if($errors->has('role')) is-invalid @endif"  data-toggle="select2">
                                        <option value="">Semua Group</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $row->roles()->first()->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" id="password"  name="password"  placeholder="Password" value="{{ old('password') }}">                                    
                                    @error('password')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Password Confirmation</label>
                                    <input type="password" class="form-control @if($errors->has('confirm_password')) is-invalid @endif" id="confirm_password"  name="confirm_password"  placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                                    @error('confirm_password')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-amdbtn waves-effect waves-light">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
@endsection
