@extends('layouts.admin')

@section('title', 'Transactions')

@section('admin-title', 'Daftar Transaksi')

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
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>Transaction Code</th>
                    <th>Customer</th>
                    <th>Jumlah Produk</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
                @foreach($transactions as $transaction)
                <tr class="
                @switch($transaction->status)
                    @case(0)
                        table-default
                        @break
                    @case(1)
                        table-warning
                        @break
                    @case(2)
                        table-success
                        @break
                    @case(3)
                        table-primary
                        @break
                    @case(4)
                        table-info
                        @break
                    @case(5)
                        table-danger
                        @break
                @endswitch
                ">
                    <td>{{$loop->index+1}}</td>
                    <td><a href="{{route('admin-transaction-detail', ['id' => $transaction->id])}}">{{$transaction->trans_no}}</a></td>
                    <td>{{$transaction->customer->name}}</td>
                    <td>{{$transaction->transactionDetails()->sum('quantity')}}</td>
                    <td>Rp {{number_format($transaction->transactionDetails()->sum('total'))}}</td>
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
                    <td>{{date_format($transaction->created_at,"d M Y")}}</br>{{date_format($transaction->created_at,"H:i")}}</td>
                    <td>{{date_format($transaction->updated_at,"d M Y")}}</br>{{date_format($transaction->updated_at,"H:i")}}</td>
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