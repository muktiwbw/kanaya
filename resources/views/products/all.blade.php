@extends('layouts.admin')

@section('title', 'Products')

@section('admin-title', 'Daftar Produk')

@section('admin-content')
<div class="row">
    <div class="col-12" style="margin-bottom: 15px">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-success" href="{{route('admin-products-create')}}">+ Tambahkan Produk</a>
            </div>
            <div class="col-6 text-right">
                @if(!$products->onFirstPage())<a href="{{$products->url(1)}}" class="btn btn-dark"><<</a>
                <a href="{{$products->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
                @for($i=0;$i<$products->lastPage();$i++)
                    <a href="{{$products->url($i+1)}}" class="btn @if($products->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
                @endfor
                @if($products->hasMorePages())<a href="{{$products->nextPageUrl()}}" class="btn btn-dark">></a>
                <a href="{{$products->url($products->lastPage())}}" class="btn btn-dark">>></a>@endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Preview</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Available</th>
                <th>Rent Process</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
            @foreach($products as $product)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$product->code}}</td>
                <td><a href="{{route('admin-products-edit', ['id' => $product->id])}}">{{$product->name}}</a></td>
                <td>
                    <img style="border-style: none;" width="100" src="{{asset('img/'.$product->images()->first()->url)}}" alt="{{$product->name}}">
                </td>
                <td>Rp {{number_format($product->price)}}</td>
                <td>{{$product->stock}}</td>
                <td>{{$product->available}}</td>
                <td>{{$product->rent}}</td>
                <td>{{date_format($product->created_at,"d M Y")}}</br>{{date_format($product->created_at,"H:i")}}</td>
                <td>{{date_format($product->updated_at,"d M Y")}}</br>{{date_format($product->updated_at,"H:i")}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-12 text-right">
        @if(!$products->onFirstPage())<a href="{{$products->url(1)}}" class="btn btn-dark"><<</a>
        <a href="{{$products->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
        @for($i=0;$i<$products->lastPage();$i++)
            <a href="{{$products->url($i+1)}}" class="btn @if($products->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
        @endfor
        @if($products->hasMorePages())<a href="{{$products->nextPageUrl()}}" class="btn btn-dark">></a>
        <a href="{{$products->url($products->lastPage())}}" class="btn btn-dark">>></a>@endif
    </div>
</div>
@endsection