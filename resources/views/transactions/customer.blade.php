@extends('layouts.default')

@section('title', 'Transaksi')

@section('default-content')
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-credit-card" aria-hidden="true"></i> Transaksi</h1>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if($transactions->count() == 0)
            <h3>Belum ada transaksi</h3>
            @else
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>Transaction Code</th>
                    <th>Jumlah Produk</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Peminjaman</th>
                    <th>Pengembalian</th>
                    <th>Status</th>
                </tr>
                @foreach($transactions as $transaction)
                <tr class="
                    @switch($transaction->status)
                        @case(3)
                            table-warning
                            @break
                        @case(4)
                            table-success
                            @break
                        @case(5)
                            table-danger
                            @break
                    @endswitch
                ">
                    <td>{{$loop->index+1}}</td>
                    <td>{{$transaction->trans_no}}</td>
                    <td>{{$transaction->transactionDetails()->sum('quantity')}}</td>
                    <td>
                        <ul>
                            @foreach($transaction->transactionDetails as $item)
                                <li>{{$item->product->name}} ({{$item->quantity}})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>Rp {{number_format($transaction->transactionDetails()->sum('total'))}}</td>
                    <td>{{date('d F Y', strtotime($transaction->start_date))}}</td>
                    <td>{{date('d F Y', strtotime($transaction->end_date))}}</td>
                    <td>
                        @switch($transaction->status)
                            @case(3)
                                Barang sedang dipinjam
                                @break
                            @case(4)
                                Barang telah dikembalikan
                                @break
                            @case(5)
                                Barang terlambat dikembalikan
                                @break
                        @endswitch
                    </td>
                </tr>
                @endforeach
            </table>
            @endif
        </div>
    </div>
</div>
@endsection