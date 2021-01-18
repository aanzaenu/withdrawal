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
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ env('APP_NAME', 'Laravel') }}</a></li>
                            <li class="breadcrumb-item active">{{ $pagetitle }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $pagetitle }}</h4>
                </div>
            </div>
        </div>
        @include('backend.layout.allert')
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <div class="d-block w-100 mb-1">
                        <div class="row">
                            <div class="col-lg-6">
                                <form class="exe" method="POST" action="{{ route('admin.'.$uri.'.deletemass') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-xl-8 mb-3">
                                            <div class="input-group">
                                                <select name="pilihexe" class="custom-select">
                                                    <option value="">Pilih Aksi</option>
                                                    <option value="1">Hapus Terpilih</option>
                                                </select>
                                                <input type="hidden" name="ids"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-amdbtn waves-effect waves-light" type="submit">Eksekusi</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-6">
                                <form class="" method="GET" action="{{ route('admin.'.$uri.'.search') }}">
                                    <div class="row">
                                        <div class="col-lg-3">
                                        </div>
                                        <div class="col-lg-9 mb-3">
                                            <div class="input-group">
                                                <input type="text" name="query" class="form-control" placeholder="Cari Sesuatu" value="{{ request()->get('query') }}"/>
                                                <select name="banks" class="custom-select">
                                                    <option value="">Semua Bank</option>
                                                    @foreach ($banks as $item)
                                                        <option value="{{ $item->id }}" {{ request()->get('banks') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="users" class="custom-select">
                                                    <option value="">Semua User</option>
                                                    @foreach ($users as $item)
                                                        <option value="{{ $item->id }}" {{ request()->get('users') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-amdbtn waves-effect waves-light" type="submit">Cari</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if(count($lists) > 0)
                    <div class="table-responsive" style="padding-bottom: 155px;">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead class="bg-amdbtn text-white text-uppercase">
                                <tr>
                                    <th>
                                        <div class="checkbox checkbox-amdbtn checkbox-single">
                                            <input type="checkbox" class="cekall">
                                            <label></label>
                                        </div>                                        
                                    </th>
                                    <th>Nama Bank</th>
                                    <th>Nominal</th>
                                    <th>Pengaju</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lists as $key=>$list)
                                <tr class="rows-{{ $list->id }}">
                                    <th scope="row">
                                        <div class="checkbox checkbox-amdbtn checkbox-single">
                                            <input type="checkbox" name="ceking" data-id="{{ $list->id }}">
                                            <label></label>
                                        </div>
                                    </th>
                                    <td>{{ $list->banks ? $list->banks->name.' - '.$list->banks->bankname.' Rp. '.number_format($list->banks->saldo) : '' }}</td>
                                    <td>Rp. {{ number_format($list->nominal) }}</td>
                                    <td>{{ $list->users ? $list->users->name : '' }}</td>
                                    <td>
                                        @if ($list->status == 0)
                                            <span class="rounded px-2 badge-sm btn-danger" style="white-space: nowrap">
                                                Belum Diproses
                                            </span>
                                        @elseif($list->status == 1)
                                            <span class="rounded px-2 badge btn-amdbtn" style="white-space: nowrap">
                                                Diterima
                                            </span>
                                        @else
                                            <span class="rounded px-2 badge btn-secondary" style="white-space: nowrap">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ date('d, M Y H:i', strtotime($list->created_at)) }}</td>
                                    <td >
                                        @if ($list->status == 0)
                                            <a href="#" class="btn btn-amdbtn btn-sm update" data-id="{{ $list->id }}" data-bank="{{ $list->bank_id }}" data-status="{{ $list->status }}" data-user="{{ $list->user_id }}" data-nominal="{{ number_format($list->nominal) }}">
                                                Edit
                                            </a>
                                        @endif
                                        @if (is_admin() || is_subadmin())
                                            <form action="{{ route('admin.'.$uri.'.destroy', $list->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        @endif
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="d-block text-center">
                        <h3>Data tidak ditemukan</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ $lists->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @include('backend.layout.ajaxmodal')
    <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <form class="formupdate" method="POST" action="{{ route('admin.'.$uri.'.apdet') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="0"/>
                <div class="modal-header">
                  <h5 class="modal-title d-block w-100 text-center" id="updateLabel">Modal title</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <select name="banks" class="custom-select" disabled>
                            <option value="0">Tidak ada Bank</option>
                            @foreach ($banks as $item)
                                <option value="{{ $item->id }}">{{ $item->name.' - '.$item->bankname  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pengaju</label>
                        <select name="users" class="custom-select" disabled>
                            <option value="0">Tidak ada User</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" class="form-control" id="nominal"  name="nominal" disabled>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="custom-select" data-toggle="select2">
                            <option value="0">Belum Diproses</option>
                            <option value="1">Diterima</option>
                            <option value="2">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm tutup">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">Proses</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="application/javascript">
        $(window).on('load', function(){
            $('.cekall').change(function(){
                if($(this).is(':checked', true))
                {
                    $('input[name="ceking"]').prop('checked', true);
                }else{
                    $('input[name="ceking"]').prop('checked', false);
                }
            });
            $('input[name="ceking"]').change(function(){
                if($('input[name="ceking"]:checked').length == $('input[name="ceking"]').length)
                {
                    $('.cekall').prop('checked', true);
                }else{
                    $('.cekall').prop('checked', false);
                }
            });
            $('form.exe').submit(function(e){
                var hasil = $('select[name="pilihexe"]').val();
                if(hasil == 1)
                {
                    var arr = [];
                    $('input[name="ceking"]:checked').each(function(){
                        arr.push($(this).attr('data-id'));
                    });
                    if(arr.length > 0)
                    {
                        var strarr = arr.join(',');
                        $('input[name="ids"]').val(strarr);
                        $(this).submit();
                    }else{
                        e.preventDefault();
                    }
                }else{
                    e.preventDefault();
                }
            });
            $('.update').on('click', function(e){
                e.preventDefault();
                onModal = true;
                var id = $(this).data('id');
                var bank = $(this).data('bank');
                var status = $(this).data('status');
                var user = $(this).data('user');
                var nominal = $(this).data('nominal');
                $('#update select[name="status"]').val(status);
                $('#update select[name="banks"]').val(bank);
                $('#update select[name="users"]').val(user);
                $('input[name="id"]').val(id);
                $('input[name="nominal"]').val(nominal);
                $('#updateLabel').html('#'+ id);
                $('select[data-toggle="select2"]').trigger('change');
                $('#update').modal({
                    backdrop: 'static'
                });
            });
            $('.tutup').on('click', function(){
                $('#update').modal('hide');
            });
        });
    </script>
@endsection
