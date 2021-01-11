@extends('backend.layout.app')
@section('css')
    <link href="{{asset('backend/libs/animate.css/animate.css.min.css')}}" rel="stylesheet" type="text/css" />
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
                                                    @if (!is_wd())
                                                        <a href="{{ route('admin.'.$uri.'.create') }}" class="btn btn-dark waves-effect waves-light">Request Witdrawal</a>
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
                    <div class="table-responsive" style="padding-bottom: 155px;">
                        <table class="table mytable table-hover mb-0">
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
                                    <th class="sorting @if($order_by =='time') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$bank.$operator.'orderby=time&order='.$urut }}">
                                            Waktu Transfer
                                        </a>
                                    </th>
                                    <th>Status</th>
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
                                    @if (!is_cs())
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-amdbtn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe-more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('admin.'.$uri.'.edit', $list->id) }}">Edit</a>
                                                    <div class="dropdown-divider"></div>                                              
                                                    <form action="{{ route('admin.'.$uri.'.destroy', $list->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
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
        });
    </script>
@endsection
