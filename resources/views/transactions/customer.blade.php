@extends('layouts.default')

@section('title', 'Transaksi')

@section('default-content')
@page_title(['title' => 'Riwayat Transaksi'])@endpage_title
<div class="row">
<div class="col-12">
        @if($transactions->count() == 0)
            <h3 class="text-center">
                Belum ada transaksi yang masuk<br>
                <a href="{{route('catalog')}}">Sudah cek katalog?</a>
            </h3>
            @else
            <table class="table table-hover table-bordered">
                <tr class="thead-dark">
                    <th>#</th>
                    <th>Kode Transaksi</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Jumlah Item</th>
                    <th>Total (Rp)</th>
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
                    <td>{{$transaction->quantity}}</td>
                    <td>{{number_format($transaction->total)}}</td>
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
</div>
@endsection