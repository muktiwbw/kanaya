@extends('layouts.default')

@section('title', 'Cart')

@section('default-content')
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</h1>
        <hr>
    </div>
</div>
@if($transaction)
<div class="row">
    <div class="col-12">
        @foreach($transaction->transactionDetails as $item)
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-12">
                <h5>{{$item->product->name}}</h5>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-6" style="height: 100px; overflow: hidden;">
                        @if($item->product->images()->first())
                        <img class="mw-100" src="{{asset('img/'.$item->product->images()->first()->path)}}" alt="{{$item->product->name}}">
                        @endif
                    </div>
                    <div class="col-md-9 col-sm-6 col-6 text-right">
                        <div class="row">
                            <div class="col-12">
                                {{$item->product->available}} available
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 pt-2 pb-2">
                                <form action="{{route('cart-update', ['id' => $item->product->id])}}" class="form-inline float-right" method="post">
                                    <input class="form-control mr-sm-2" type="number" name="new_quantity" value="{{$item->quantity}}" min="0" max="{{$item->product->available}}">
                                    <button type="submit" class="btn btn-outline-success">Update</button>
                                    @method('PATCH')
                                    @csrf
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{$item->quantity}} x Rp {{number_format($item->product->price)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                = Rp {{number_format($item->quantity*$item->product->price)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 pt-2 pb-2">
                                <form action="{{route('cart-delete', ['id' => $item->product->id])}}" class="form-inline float-right" method="post">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-6 text-left">
                <h5>Grand Total</h5>
            </div>
            <div class="col-6 text-right">
                <h5>Rp {{number_format($transaction->transactionDetails()->sum('total'))}}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 pt-2 pb-2">
                <a href="#" class="btn btn-success btn-block"><h4><i class="fa fa-credit-card" aria-hidden="true"></i> Checkout</h4></a>
            </div>
        </div>
    </div>
</div>
@else
<h2>Tidak ada item di keranjang</h2>
@endif
@endsection