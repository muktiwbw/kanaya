@extends('layouts.default')

@section('title', 'Products')

@section('default-content')
@component('components.admin-page-navigation', ['nav_product' => 'active'])@endcomponent
<div class="row">
    <div class="col-12" style="margin-bottom: 15px">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-success" href="{{route('admin-products-create')}}">+ Tambahkan Produk</a>
            </div>
            <div class="col-6 text-right">
            @if($products->count() > 0)
                @if(!$products->onFirstPage())<a href="{{$products->url(1)}}" class="btn btn-dark"><<</a>
                <a href="{{$products->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
                @for($i=0;$i<$products->lastPage();$i++)
                    <a href="{{$products->url($i+1)}}" class="btn @if($products->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
                @endfor
                @if($products->hasMorePages())<a href="{{$products->nextPageUrl()}}" class="btn btn-dark">></a>
                <a href="{{$products->url($products->lastPage())}}" class="btn btn-dark">>></a>@endif
            @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if($products->count() == 0)
        <h3>Belum ada produk yang ditambahkan</h3>
        @else
        <table class="table table-hover table-bordered">
            <tr class="thead-dark">
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Preview</th>
                <th>Price</th>
                <th>Size</th>
                <th>Stock</th>
                <th>Available</th>
                <th>Rent Process</th>
            </tr>
            @foreach($products as $product)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$product->code}}</td>
                <td><a href="{{route('admin-products-edit', ['code' => $product->code])}}">{{$product->name}}</a></td>
                <td>
                    @if($product->url)
                    <img style="border-style: none;" width="100" src="{{asset('img/'.$product->url)}}" alt="{{$product->name}}">
                    @else
                    Tidak ada gambar
                    @endif
                </td>
                <td>Rp {{number_format($product->price)}}</td>
                <td>
                    @foreach($product->sizes as $size)
                    ({{strtoupper($size->size)}}) @if(!$loop->last) <br> @endif
                    @endforeach
                </td>
                <td>
                    @foreach($product->sizes as $size)
                    {{$size->stock}} @if(!$loop->last) <br> @endif
                    @endforeach
                </td>
                <td>
                    @foreach($product->sizes as $size)
                    {{$size->available}} @if(!$loop->last) <br> @endif
                    @endforeach
                </td>
                <td>
                    @foreach($product->sizes as $size)
                    {{$size->rent}} @if(!$loop->last) <br> @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>
    <div class="col-12 text-right">
        @if($products->count() > 0)
            @if(!$products->onFirstPage())<a href="{{$products->url(1)}}" class="btn btn-dark"><<</a>
            <a href="{{$products->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
            @for($i=0;$i<$products->lastPage();$i++)
                <a href="{{$products->url($i+1)}}" class="btn @if($products->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
            @endfor
            @if($products->hasMorePages())<a href="{{$products->nextPageUrl()}}" class="btn btn-dark">></a>
            <a href="{{$products->url($products->lastPage())}}" class="btn btn-dark">>></a>@endif
        @endif
    </div>
</div>
@endsection