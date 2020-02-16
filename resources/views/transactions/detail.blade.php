@extends('layouts.admin')

@section('title', $transaction->trans_no)

@section('admin-title', $transaction->customer->name.' - '.$transaction->trans_no)

@section('admin-content')
<div class="row">
    <div class="col-6">
        @foreach($transaction->transactionDetails as $item)
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-12">
                <h5>{{$item->product->name}}</h5>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6" style="height: 100px; overflow: hidden;">
                        @if($item->product->images()->first())
                        <img class="mw-100" src="{{asset('img/'.$item->product->images()->first()->path)}}" alt="{{$item->product->name}}">
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="row">
                            <div class="col-12">
                                <strong>Kode Produk:</strong> {{$item->product->code}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Quantity:</strong> {{$item->quantity}} pcs
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6>Notes</h6>
                                <p>{{$item->notes}}</p>
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
<div class="row">
    <div class="col-12">
        <h5>Total Pembayaran</h5>
        <h6>Rp {{number_format($transaction->transactionDetails()->sum('total'))}}</h6>
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