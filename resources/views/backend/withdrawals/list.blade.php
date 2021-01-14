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
        @if (!is_cs())
            <div class="row">
                <div class="col-md-{{ count($banks) > 4 ? '6' : '12' }}">
                    <div class="card-box">
                        <div class="d-block w-100 mb-1">
                            <div class="table-responsive">
                                <table class="table table-sm table-centered table-nowrap table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Nama</th>
                                            <th>Atas Nama Bank</th>
                                            <th>No. Rekening</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ofset = 0;
                                            $ofset = ceil(count($banks) / 2);
                                            if(count($banks) - $ofset <= $ofset){
                                                $ofset = $ofset+1;
                                            }
                                        @endphp
                                        @foreach ($banks as $key=>$list)
                                        @php
                                            if ($key + 1 == $ofset && count($banks) > 4) {
                                                break;
                                            }
                                        @endphp
                                            <tr>
                                                <td>{!! $list->image ? '<img src="'.asset($list->image).'" class="img-thumbnail" style="max-width:30px;"/>' : '' !!}</td>
                                                <td>{{ $list->name }}</td>
                                                <td>{{ $list->bankname }}</td>
                                                <td>{{ $list->rec }}</td>
                                                <td>Rp. {{ number_format($list->saldo) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if (count($banks) > 4)
                <div class="col-md-6">
                    <div class="card-box">
                        <div class="d-block w-100 mb-1">
                            <div class="table-responsive">
                                <table class="table table-sm table-centered table-nowrap table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Nama</th>
                                            <th>Atas Nama Bank</th>
                                            <th>No. Rekening</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banks as $key=>$list)
                                            @php
                                                if($key+1 < $ofset)
                                                {
                                                    continue;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{!! $list->image ? '<img src="'.asset($list->image).'" class="img-thumbnail" style="max-width:30px;"/>' : '' !!}</td>
                                                <td>{{ $list->name }}</td>
                                                <td>{{ $list->bankname }}</td>
                                                <td>{{ $list->rec }}</td>
                                                <td>Rp. {{ number_format($list->saldo) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                
                @endif
            </div>            
        @endif
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
                                                @if (!is_cs())                                                    
                                                    <select name="pilihexe" class="custom-select">
                                                        <option value="">Pilih Aksi</option>
                                                        <option value="1">Hapus Terpilih</option>
                                                    </select>
                                                @endif
                                                <input type="hidden" name="ids"/>
                                                <div class="input-group-append">
                                                    @if (!is_cs())
                                                        <button class="btn btn-amdbtn waves-effect waves-light" type="submit">Eksekusi</button>
                                                    @endif
                                                    @if (!is_wd())
                                                        <a href="{{ route('admin.'.$uri.'.create') }}" class="btn btn-dark waves-effect waves-light">Form Witdrawal</a>
                                                    @endif
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
                                                @if (!is_cs())
                                                    <select name="bank" class="custom-select">
                                                        <option value="">Semua Bank</option>
                                                        @foreach ($banks as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == request()->get('bank') ? 'selected' : ''}}>{{ $item->name.' - '.$item->bankname  }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="operator" class="custom-select">
                                                        <option value="">Semua Operator</option>
                                                        @foreach ($operators as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == request()->get('operator') ? 'selected' : ''}}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
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
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead>
                                <tr>
                                    @if (!is_cs())
                                        <th>
                                            <div class="checkbox checkbox-amdbtn checkbox-single">
                                                <input type="checkbox" class="cekall">
                                                <label></label>
                                            </div>                                        
                                        </th>
                                    @endif
                                    <?php 
                                        $uris = url()->current();
                                        $order_by = request()->get('orderby');
                                        $order = request()->get('order');
                                        $urut = 'asc';
                                        if(!empty($order))
                                        {
                                            if($order == 'asc')
                                            {
                                                $urut = 'desc';
                                            }else{
                                                $urut = 'asc';
                                            }
                                        }
                                        $kueri = '';
                                        if(!empty(request()->get('query')))
                                        {
                                            $kueri = 'query='.request()->get('query').'&';
                                        }
                                        $bank = '';
                                        if(!empty(request()->get('bank')))
                                        {
                                            $bank = 'bank='.request()->get('bank').'&';
                                        }
                                        $operator = '';
                                        if(!empty(request()->get('operator')))
                                        {
                                            $operator = 'operator='.request()->get('operator').'&';
                                        }
                                    ;?>
                                    @if (!is_cs())
                                        <th>
                                            Operator
                                        </th>                                        
                                    @endif
                                    <th class="sorting @if($order_by =='bank') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$bank.$operator.'orderby=bank&order='.$urut }}">
                                            Nama dan Rec
                                        </a>
                                    </th>
                                    <th class="sorting @if($order_by =='nominal') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$bank.$operator.'orderby=nominal&order='.$urut }}">
                                            Nominal
                                        </a>
                                    </th>
                                    <th>Bank</th>
                                    <th class="sorting @if($order_by =='fee') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$bank.$operator.'orderby=fee&order='.$urut }}">
                                            Biaya Admin
                                        </a>
                                    </th>
                                    <th class="sorting @if($order_by =='updated_at') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$bank.$operator.'orderby=updated_at&order='.$urut }}">
                                            Waktu Transfer
                                        </a>
                                    </th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th>
                                    @if (!is_cs())
                                        <th>Aksi</th>                                        
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lists as $key=>$list)
                                <tr class="rows-{{ $list->id }}">
                                    @if (!is_cs())
                                        <th scope="row">
                                            <div class="checkbox checkbox-amdbtn checkbox-single">
                                                <input type="checkbox" name="ceking" data-id="{{ $list->id }}">
                                                <label></label>
                                            </div>
                                        </th>
                                        <td>{{ $list->op ? $list->op->name : '-' }}</td>
                                    @endif
                                    <td>{!! $list->bank.' - '.$list->bankname.'<br/>'.$list->bankrec !!}</td>
                                    <td>Rp. {{ number_format($list->nominal) }}</td>
                                    <td>{{ $list->banks()->first() ? $list->banks()->first()->name : '-' }}</td>
                                    <td>Rp. {{ number_format($list->fee) }}</td>
                                    <td>{{ $list->time ? date('d, M Y H:i', strtotime($list->time)) : '-' }}</td>
                                    <td>
                                        @if ($list->status == 0)
                                        <span class="rounded px-2 badge-sm btn-danger" style="white-space: nowrap">
                                            Belum Diproses
                                        </span>
                                        @else
                                        <span class="rounded px-2 badge btn-amdbtn" style="white-space: nowrap">
                                            Done
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                       {!! $list->image ? '<a class="rounded px-2 badge btn-amdbtn" href="'.asset($list->image).'" target="_blank">Image</a>' : '-' !!}
                                    </td>
                                    @if (!is_cs())
                                        <td >
                                            @if (!$list->banks()->first() && $list->status == 0)
                                                <a href="#" class="btn btn-amdbtn btn-sm update" data-id="{{ $list->id }}" data-bank="{{ $list->banks()->first() ? $list->banks()->first()->id : '' }}" data-status="{{ $list->status }}">
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
                                    @endif
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
                        <label>Withdrawal Bank</label>
                        <select name="banks" class="custom-select" data-toggle="select2">
                            <option value="0">Pilih Bank</option>
                            @foreach ($banks as $item)
                                <option value="{{ $item->id }}">{{ $item->name.' - '.$item->bankname  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="custom-select" data-toggle="select2">
                            <option value="0">Belum diproses</option>
                            <option value="1">Done</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fee">Biaya Admin</label>
                        <input type="text" class="form-control" id="fee"  name="fee"  placeholder="5.000.000" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true">
                    </div>
                    <div class="form-group">
                        <label for="file">Bukti Transfer</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="inputGroupFile04" accept="image/*">
                                <label class="custom-file-label" for="inputGroupFile04">Pilih file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm tutup">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
                $('#update select[name="status"]').val(status);
                $('#update select[name="banks"]').val(bank);
                $('input[name="id"]').val(id);
                $('#updateLabel').html('#'+ id);
                $('#update').modal({
                    backdrop: 'static'
                });
            });
            $('.tutup').on('click', function(){
                $('#update').modal('hide');
            });
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        });
    </script>
@endsection
