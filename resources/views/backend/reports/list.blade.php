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
                            </div>
                            <div class="col-lg-6">
                                <form class="" method="POST" action="{{ route('admin.'.$uri.'.download') }}">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="from" value="{{ request()->get('from') }}"/>
                                    <input type="hidden" name="to" value="{{ request()->get('to') }}"/>
                                    <input type="hidden" name="operator" value="{{ request()->get('operator') }}"/>
                                    <input type="hidden" name="banks" value="{{ request()->get('banks') }}"/>
                                    <input type="hidden" name="status" value="{{ request()->get('status') }}"/>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <div class="input-group-append  ml-auto">
                                                    <button class="btn btn-amdbtn waves-effect waves-light" type="submit">Download</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if($lists->total() > 0)
                    <div class="table-responsive" style="padding-bottom: 155px;">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox checkbox-amdbtn checkbox-single">
                                            <input type="checkbox" class="cekall">
                                            <label></label>
                                        </div>                                        
                                    </th>
                                    <th>
                                        Operator
                                    </th> 
                                    <th>
                                        Nama dan Rec
                                    </th>
                                    <th>
                                        Nominal
                                    </th>
                                    <th>Bank</th>
                                    <th>Biaya Admin</th>
                                    <th>
                                        Waktu Transfer
                                    </th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th>
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
                                    <td>{{ $list->op ? $list->op->name : '-' }}</td>
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
                                        {!! $list->image ? '<a class="btn btn-sm btn-amdbtn" href="'.asset($list->image).'" target="_blank">Image</a>' : '-' !!}
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
@endsection
@section('script')
@endsection
