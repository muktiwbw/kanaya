@extends('layouts.admin')

@section('title', 'Daftar Transaksi Selesai')

@section('admin-title', 'Daftar Transaksi Selesai')

@section('admin-content')
<div class="row">
    <div class="col-12" style="margin-bottom: 15px">
        <div class="row">
            <div class="col-12 text-right">
            @if($transactions->count() > 0)
                @if(!$transactions->onFirstPage())<a href="{{$transactions->url(1)}}" class="btn btn-dark"><<</a>
                <a href="{{$transactions->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
                @for($i=0;$i<$transactions->lastPage();$i++)
                    <a href="{{$transactions->url($i+1)}}" class="btn @if($transactions->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
                @endfor
                @if($transactions->hasMorePages())<a href="{{$transactions->nextPageUrl()}}" class="btn btn-dark">></a>
                <a href="{{$transactions->url($transactions->lastPage())}}" class="btn btn-dark">>></a>@endif
            @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if($transactions->count() == 0)
            <h3>Belum ada transaksi yang masuk</h3>
            @else
            <table class="table table-hover table-bordered">
                <tr class="thead-dark">
                    <th>#</th>
                    <th>Kode Transaksi</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Jumlah Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                </tr>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$transaction->trans_no}}</td>
                    <td>{{$transaction->customer->name}}</td>
                    <td>
                        <ul>
                        @foreach($transaction->transactionLogs as $tl)
                            <li><a href="/product/{{$tl->code}}">{{$tl->name}}</a> ({{strtoupper($tl->size)}}) - {{$tl->quantity}} pcs</li>
                        @endforeach
                        </ul>
                    </td>
                    <td>{{$transaction->transactionLogs()->count()}}</td>
                    <td>Rp {{number_format($transaction->transactionLogs()->sum('price'))}}</td>
                    <td>
                        @switch($transaction->status)
                            @case(0)
                                Cart
                                @break
                            @case(1)
                                Menunggu Pembayaran
                                @break
                            @case(2)
                                Pembayaran Diterima
                                @break
                            @case(3)
                                Barang Diambil
                                @break
                            @case(4)
                                Barang Kembali
                                @break
                            @case(5)
                                Terlambat Mengembalikan
                                @break
                        @endswitch
                    </td>
                    <td>{{date_format(DateTime::createFromFormat('Y-m-d', $transaction->start_date),"d M Y")}}
                    <td>{{date_format(DateTime::createFromFormat('Y-m-d', $transaction->end_date),"d M Y")}}
                </tr>
                @endforeach
            </table>
            @endif
        </div>
    </div>
    <div class="col-12 text-right">
    @if($transactions->count() > 0)
        @if(!$transactions->onFirstPage())<a href="{{$transactions->url(1)}}" class="btn btn-dark"><<</a>
        <a href="{{$transactions->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
        @for($i=0;$i<$transactions->lastPage();$i++)
            <a href="{{$transactions->url($i+1)}}" class="btn @if($transactions->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
        @endfor
        @if($transactions->hasMorePages())<a href="{{$transactions->nextPageUrl()}}" class="btn btn-dark">></a>
        <a href="{{$transactions->url($transactions->lastPage())}}" class="btn btn-dark">>></a>@endif
    @endif
    </div>
</div>
@endsection