@extends('backend.layout.app')
@section('css')
    <link href="{{asset('backend/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
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
        <form method="GET" action="{{ route('admin.'.$uri.'.search') }}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from">Dari</label>
                                            <input type="text" class="form-control @if($errors->has('from')) is-invalid @endif" id="from"  name="from"  placeholder="2020-11-11" value="" data-toggle="input-flat">
                                            @error('from')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to">Sampai</label>
                                            <input type="to" class="form-control @if($errors->has('to')) is-invalid @endif" id="to"  name="to"  placeholder="2020-11-11" value="" data-toggle="input-flat">
                                            @error('to')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="operator">Nama Operator</label>
                                    <select id="operator" name="operator" class="form-control @if($errors->has('operator')) is-invalid @endif"  data-toggle="select2">
                                        <option value="">Semua Operator</option>
                                        @foreach ($operators as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('operator')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="banks">Withdrawal dari Bank</label>
                                    <select id="banks" name="banks" class="form-control @if($errors->has('banks')) is-invalid @endif"  data-toggle="select2">
                                        <option value="">Semua Bank</option>
                                        @foreach ($banks as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name.' - '.$item->bankname.' - '.$item->rec }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('banks')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control @if($errors->has('status')) is-invalid @endif"  data-toggle="select2">
                                        <option value="all">Semua Status</option>
                                        <option value="0">Belum diproses</option>
                                        <option value="1">Done</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-amdbtn waves-effect waves-light">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script src="{{asset('backend/libs/flatpickr/flatpickr.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="input-flat"]').flatpickr({
            dateFormat: "Y-m-d"
        });
    });
</script>
@endsection
