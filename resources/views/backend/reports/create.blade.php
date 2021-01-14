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
        <form method="POST" action="{{ route('admin.'.$uri.'.store') }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="bank">Bank User</label>
                                    <input type="text" class="form-control @if($errors->has('bank')) is-invalid @endif" id="bank"  name="bank"  placeholder="BNI" value="{{ old('bank') }}">
                                    @error('bank')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="bankname">Atas Nama Bank User</label>
                                    <input type="text" class="form-control @if($errors->has('bankname')) is-invalid @endif" id="bankname"  name="bankname"  placeholder="Steve Job" value="{{ old('bankname') }}">
                                    @error('bankname')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="bankrec">No. Rekening Bank User</label>
                                    <input type="text" class="form-control @if($errors->has('bankrec')) is-invalid @endif" id="bankrec"  name="bankrec"  placeholder="5632554855" value="{{ old('bankrec') }}">
                                    @error('bankrec')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nominal">Nominal</label>
                                    <input type="text" class="form-control @if($errors->has('nominal')) is-invalid @endif" id="nominal"  name="nominal"  placeholder="5.000.000" value="{{ old('nominal') }}" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" >
                                    @error('nominal')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @if (!is_cs())
                                <div class="form-group">
                                    <label for="operator">Operator</label>
                                    <select id="operator" name="operator" class="form-control @if($errors->has('operator')) is-invalid @endif"  data-toggle="select2">
                                        <option value="">Semua Operator</option>
                                        @foreach ($operators as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == old('operator') ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('operator')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @endif
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
