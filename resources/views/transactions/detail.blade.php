@extends('layouts.default')

@section('title', $transaction->trans_no)

@section('default-content')
<div class="row pb-4">
    <div class="col-12">
        <a href="{{route('admin-products-list')}}"><i class="fa fa-chevron-left" aria-hidden="true"></i> Kembali</a>
    </div>
</div>
@page_title(['title' => 'Detail Transaksi'])@endpage_title
<div class="row">
    <div class="col-6">
        @foreach($transaction->products as $item)
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-6">
                <a href="/product/{{$item->code}}"><h5 class="mb-4">{{$item->name}}</h5></a>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6" style="height: 100px; overflow: hidden;">
                        @if($item->url)
                        <img class="mw-100" src="{{asset('img/'.$item->url)}}" alt="{{$item->url}}">
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="row">
                            <div class="col-6">
                                <h5>Kode Produk</h5> 
                            </div>
                            <div class="col-6">
                                <h5>[ {{$item->code}} ]</h5>
                            </div>
                            <div class="col-12"><hr></div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Ukuran</strong> 
                            </div>
                            <div class="col-12">
                                <ul>
                                    @foreach($item->sizes as $size)
                                    <li>
                                        <div class="row">
                                            <div class="col-6">{{strtoupper($size->size)}}</div>
                                            <div class="col-6">{{$size->quantity}} pcs</div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                <hr>
                            </div>
                            <div class="col-12 text-right">
                                <strong>{{$item->total_count}} x Rp {{number_format($item->price)}}</strong>
                            </div>
                            <div class="col-12 text-right">
                                <strong>= Rp {{number_format($item->total_price)}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-12">
                <h5>Bukti Pembayaran</h5>
            </div>
            <div class="col-12">
                @if($transaction->receipt)
                    <a href="{{asset('img/'.$transaction->receipt)}}" alt="{{$transaction->trans_no}}"><img class="img-fluid" style="height:500px;" src="{{asset('img/'.$transaction->receipt)}}" alt="{{$transaction->trans_no}}"></a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row pb-2">
    <div class="col-12">
        <h5>Waktu Peminjaman</h5>
        <strong>{{date('d F Y', strtotime($transaction->start_date))}} - {{date('d F Y', strtotime($transaction->end_date))}}</strong>
    </div>
</div>
<div class="row pb-2">
    <div class="col-12">
        <h5>Total Pembayaran</h5>
        <strong>Rp {{number_format($transaction->products()->sum('price'))}}</strong>
    </div>
</div>
@if($transaction->status == 1)
<div class="row">
    <div class="col-6">
        <form action="{{route('admin-transaction-status', ['id' => $transaction->id, 'status' => 1])}}" method="post">
            <input class="btn btn-danger btn-block" type="submit" value="Tolak Pembayaran">
            @method('PATCH')
            @csrf
        </form>
    </div>
    <div class="col-6">
        <form action="{{route('admin-transaction-status', ['id' => $transaction->id, 'status' => 2])}}" method="post">
            <input class="btn btn-success btn-block" type="submit" value="Terima Pembayaran">
            @method('PATCH')
            @csrf
        </form>
    </div>
</div>
@elseif($transaction->status == 2)
<div class="row">
    <div class="col-12">
        <form action="{{route('admin-transaction-status', ['id' => $transaction->id, 'status' => 3])}}" method="post">
            <input class="btn btn-success btn-block" type="submit" value="Barang Diambil">
            @method('PATCH')
            @csrf
        </form>
    </div>
</div>
@elseif($transaction->status == 3 || $transaction->status == 5)
<div class="row">
    <div class="col-12">
        <form action="{{route('admin-transaction-status', ['id' => $transaction->id, 'status' => 4])}}" method="post">
            <input class="btn btn-warning btn-block" type="submit" value="Barang Dikembalikan">
            @method('PATCH')
            @csrf
        </form>
    </div>
</div>
@endif
@endsection