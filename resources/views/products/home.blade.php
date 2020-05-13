@extends('layouts.home')

@section('default-content')
<div class="row">
    <img class="cover-image" src="/img/assets/image/cover.jpg" alt="">
    <div class="col-12 text-center py-4">
        <ul class="nav justify-content-center">
            <li class="nav-item mx-2">
                <a class="cb cb-outline" href="{{route('catalog')}}">CEK KATALOG</a>
            </li>
        </ul>
    </div>
    <div class="col-12">
        @foreach($categories as $category)
        <div class="row item-display @if($loop->index % 2 == 1) display-bg-gray @endif">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2><span>{{ucwords(str_replace('-', ' ', $category->category))}}</span></h2>
                    </div>
                    @foreach($category->products as $product)
                    <div class="col-3" style="margin-bottom: 20px;">
                        <div class="row img-thumbnail" style="margin: 0px 0px 5px 0px; padding: 10px;">
                            <div class="col-12">
                                <a href="{{route('product-detail', ['code' => $product->code])}}">
                                    <div class="row">
                                        <div class="col-12" style="height: 200px; overflow: hidden;">
                                            @if($product->url)
                                            <img class="mw-100" src="{{asset('img/'.$product->url)}}" alt="{{$product->name}}" data-toggle="tooltip" title="{{$product->name}}">
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
                                <h5 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis" data-toggle="tooltip" title="{{$product->name}}"><a href="{{route('product-detail', ['code' => $product->code])}}" style="text-decoration:none;">{{$product->name}}</a></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="padding-right:2px;">
                                <div class="row">
                                    <div class="col-12 pb-3">Rp {{number_format($product->price)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-3 offset-md-{{($category->products->count() - 3) * -3}}">
                        <a class="cb cb-outline see-more-button" href="{{route('catalog')}}">CEK KATALOG</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection