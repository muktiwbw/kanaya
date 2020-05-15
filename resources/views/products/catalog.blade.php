@extends('layouts.default')

@section('title', 'Catalog')

@section('default-content')
@page_title(['title' => 'Katalog'])@endpage_title
<div class="row my-3">
    <div class="col-12">
        <form action="{{route('catalog')}}" method="get" class="form-inline justify-content-end">
            <label class="sr-only" for="productCategory">Category</label>
            <select class="form-control mb-2 mr-sm-2" name="category" id="productCategory">
                <option @if($queries['category'] == 'all') selected @endif value="all">All</option>
                <option @if($queries['category'] == 'dress-party') selected @endif value="dress-party">Dress Party</option>
                <option @if($queries['category'] == 'kaftan-ramadhan') selected @endif value="kaftan-ramadhan">Kaftan Ramadhan</option>
                <option @if($queries['category'] == 'kebaya-akad') selected @endif value="kebaya-akad">Kebaya Akad</option>
                <option @if($queries['category'] == 'kebaya-resepsi') selected @endif value="kebaya-resepsi">Kebaya Resepsi</option>
                <option @if($queries['category'] == 'kebaya-wisuda') selected @endif value="kebaya-wisuda">Kebaya Wisuda</option>
                <option @if($queries['category'] == 'prewedding') selected @endif value="prewedding">Prewedding</option>
                <option @if($queries['category'] == 'white-gown') selected @endif value="white-gown">White Gown</option>
            </select>
            <label class="sr-only" for="priceRangeStart">Start</label>
            <input type="number" class="form-control mb-2 mr-sm-2" id="priceRangeStart" placeholder="Harga awal" name="start_range" @if($queries['start_range']) value="{{$queries['start_range']}}" @endif>
            <label class="sr-only" for="priceRangeEnd">End</label>
            <input type="number" class="form-control mb-2 mr-sm-2" id="priceRangeEnd" placeholder="Harga akhir" name="end_range" @if($queries['end_range']) value="{{$queries['end_range']}}" @endif>
            <button class="btn btn-success form-control mb-2" type="submit">Pencarian</button>
        </form>
    </div>
    <div class="col-12 text-right"><span>Menampilkan {{$products->count()}} dari {{$products->total()}} item</span></div>
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
@if($products->total() > 20)
<div class="row py-5">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                {{$products->appends($queries)->links()}}
            </div>
        </div>
    </div>
</div>
@endif
<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>
@endsection