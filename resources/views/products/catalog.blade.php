@extends('layouts.default')

@section('title', 'Catalog')

@section('default-content')
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-shopping-bag" aria-hidden="true"></i> Catalog</h1>
        <hr>
    </div>
</div>
<div class="row">
    @foreach($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 col-6" style="margin-bottom: 20px;">
        <div class="row img-thumbnail" style="margin: 0px 0px 5px 0px; padding: 10px;">
            <div class="col-12">
                <a href="{{route('product-detail', ['id' => $product->id])}}">
                    <div class="row">
                        <div class="col-12" style="height: 200px; overflow: hidden;">
                            @if($product->images()->first())
                            <img class="mw-100" src="{{asset('img/'.$product->images()->first()->url)}}" alt="{{$product->name}}">
                            @else
                            Tidak ada gambar
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">            
                <h5><a href="{{route('product-detail', ['id' => $product->id])}}" style="text-decoration:none;">{{$product->name}}</a></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-9" style="padding-right:2px;">
                <div class="row">
                    <div class="col-12">Rp {{number_format($product->price)}}</div>
                    <div class="col-12">{{$product->available}} Available</div>
                </div>
            </div>
            <div class="col-3" style="padding-left:2px;">
                <form action="{{route('cart-add', ['id' => $product->id])}}" method="post">
                    <button style="font-size:20px;" type="submit" class="btn 
                    @if($product->available > 0) 
                    btn-outline-success
                    @else btn-error 
                    @endif btn-block" 
                    @if($product->available == 0) 
                    disabled 
                    @endif>
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                    </button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection