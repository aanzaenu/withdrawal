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
        <form method="POST" action="{{ route('admin.'.$uri.'.update', $row) }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nama Bank</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name"  name="name"  placeholder="BNI" value="{{ $row->name }}">                                    
                                    @error('name')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="bankname">Atas Nama Bank</label>
                                    <input type="text" class="form-control @if($errors->has('bankname')) is-invalid @endif" id="bankname"  name="bankname"  placeholder="Steve Job" value="{{ $row->bankname }}">                                    
                                    @error('bankname')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="rec">No. Rekening Bank</label>
                                    <input type="text" class="form-control @if($errors->has('rec')) is-invalid @endif" id="rec"  name="rec"  placeholder="562245865552" value="{{ $row->rec }}">                                    
                                    @error('rec')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="saldo">Saldo</label>
                                    <input type="text" class="form-control @if($errors->has('saldo')) is-invalid @endif" id="saldo"  name="saldo"  placeholder="5.000.000" value="{{ $row->saldo }}" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" >                                    
                                    @error('saldo')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="wd">WD</label>
                                    <select name="wd" class="custom-select" data-method="select2">
                                        <option value="">WD 1</option>
                                        <option value="1" {{ $row->wd == 1 ? 'selected' : '' }}>WD 1</option>
                                        <option value="2" {{ $row->wd == 2 ? 'selected' : '' }}>WD 2</option>
                                    </select>
                                    @error('wd')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="file">Image</label>
                                    <div class="input-group  @if($errors->has('file')) is-invalid @endif">
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input @if($errors->has('file')) is-invalid @endif" id="inputGroupFile04" accept="image/*">
                                            <label class="custom-file-label" for="inputGroupFile04">Pilih file</label>
                                        </div>
                                    </div>   
                                    @error('file')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @if ($row->image)                                    
                                    <div class="form-group">
                                        <label for="file">Selected Image</label>
                                        <div class="d-block w-100">
                                            <a href="{{ asset($row->image) }}" target="_blank">
                                                <img src="{{ asset($row->image) }}" class="img-thumbnail" style="max-width:200px;">
                                            </a>
                                        </div>
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
<script>
    $(document).ready(function(){
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
@endsection
