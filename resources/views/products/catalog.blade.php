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
            <div class="col-12">
                <form action="{{route('cart-add', ['code' => $product->code])}}" method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4 pr-1">
                                <select name="size" id="size" class="form-control">
                                    @foreach($product->sizes as $size)
                                    <option value="{{$size->size}}">{{strtoupper($size->size)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 pl-1">
                                <button class="btn btn-success btn-block"><i class="fa fa-cart-plus" aria-hidden="true"></i> Tambah</button>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>
@endsection