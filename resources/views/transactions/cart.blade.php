@extends('layouts.base')

@section('title', 'Cart')

@section('content')
<h1>Cart</h1>
<div class="row">
    <div class="col-12">
        <h4>Code: {{$transaction->trans_no}}</h4>
        <p>Items: {{$transaction->transactionDetails()->count()}}</p>
    </div>
    <div class="col-12">
        @foreach($transaction->transactionDetails as $item)
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-12">
                <h5>{{$item->product->name}}</h5>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-3" style="height: 100px; overflow: hidden;">
                        <img class="mw-100" src="{{asset('img/'.$item->product->images()->first()->path)}}" alt="{{$item->product->name}}">
                    </div>
                    <div class="col-9 text-right">
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
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-12 text-right">
                = Rp {{number_format($transaction->transactionDetails()->sum('total'))}}
            </div>
        </div>
    </div>
</div>
@endsection