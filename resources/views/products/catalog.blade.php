@extends('layouts.base')

@section('title', 'Catalog')

@section('content')
<h1>Catalog</h1>
<div class="row">
    @foreach($products as $product)
    <div class="col-3" style="margin-bottom: 20px;">
        <div class="row img-thumbnail" style="margin: 0px 0px 5px 0px; padding: 10px;">
            <div class="col-12">
                <a href="{{route('product-detail', ['id' => $product->id])}}">
                    <div class="row">
                        <div class="col-12" style="height: 200px; overflow: hidden;">
                            <img class="mw-100" src="{{asset('img/'.$product->images()->first()->url)}}" alt="{{$product->name}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">            
                            {{$product->name}}
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{route('cart-add')}}" method="post">
                    <input type="hidden" name="_product_id" value="{{$product->id}}">
                    <button type="submit" class="btn @if($product->available > 0) btn-success @else btn-error @endif btn-block" @if($product->available == 0) disabled @endif>@if($product->available > 0) Tambah ke keranjang @else Stok kosong @endif</button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection