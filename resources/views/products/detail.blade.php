@extends('layouts.default')

@section('title', $product->name)

@section('default-content')
<div class="row">
    <div class="col-4">
        <img class="img img-fluid" src="{{asset('img/'.$product->images()->first()->url)}}" alt="{{$product->name}}">
    </div>
    <div class="col-8">
        <div class="row">
            <div class="col-12">
                <h1>{{$product->name}}</h1>
            </div>
            <div class="col-12">
                <h6>Description</h6>
                <p>{{$product->notes}}</p>
                <hr>
            </div>
            <div class="col-12">
                <h6>Price</h6>
                <p>Rp {{number_format($product->price)}}</p>
                <hr>
            </div>
            <div class="col-12">
                <h6>Available</h6>
                <p>{{$product->available}}</p>
                <hr>
            </div>
            <div class="col-12">
                <a href="#" class="btn btn-outline-success btn-block"><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to cart</a>
            </div>
        </div>
    </div>
</div>
@endsection