@extends('layouts.default')

@section('title', $product->name)

@section('default-content')
<style>
    img.img.display.img-active {
        display: block;
    }

    img.img-active.img-thumbnail{
        border-width: thick;
    }

    img.img-thumbnail:hover{
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-4 mb-5">
        <div class="row">
            <div class="col-12 mw-100">
                @foreach($product->images as $image)
                <img data-value="{{$image->id}}" class="mw-100 img img-display @if($loop->index == 0) img-active @endif" @if($loop->index > 0) style="display:none;" @endif src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                @endforeach
            </div>
            <div class="col-12 pt-2">
                <div class="row">
                    <div class="col-12 preview-grid-container w-100">
                        @foreach($product->images as $image)
                        <div class="catalog-image-grid thumbnail @if($loop->index == 0) img-active @endif" style="background-image: url('/img/{{$image->url}}')" data-value="{{$image->id}}">
                            <div class="grid-overlay w-100 h-100 test"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8 mb-5">
        <div class="row">
            <div class="col-12 mb-3">
                <h1><span class="pb-1" style="border-bottom: medium solid rgb(70, 70, 70);">{{$product->name}}</span></h1>
                <span><strong>{{ucwords(str_replace('-', ' ', $product->category))}}</strong></span>
            </div>
            <div class="col-12">
                <p>{{$product->notes}}</p>
                <hr>
            </div>
            <div class="col-12">
                <h6>Harga sewa</h6>
                <p>Rp {{number_format($product->price)}}</p>
                <hr>
            </div>
            <div class="col-12">
                <form action="{{route('cart-add', ['code' => $product->code])}}" method="post">
                    <div class="row">
                        <div class="col-12">
                            <h6>Ukuran</h6>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <select name="size" id="size" class="form-control">
                                            @foreach($product->sizes as $size)
                                            <option value="{{$size->size}}" class="size-index" available-data-value="{{$size->available}}">{{strtoupper($size->size)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6>Jumlah</h6>
                        </div>
                        <div class="col-4">
                            <input id="quantity" name="quantity" type="number" min="1" @if($product->sizes[0]->available < 5) max="{{$product->sizes[0]->stock}}" @else max="5" @endif class="form-control" value="1">
                        </div>
                        <div class="col-12 mb-4">
                            Stok barang: <strong><span id="available">{{$product->sizes[0]->stock}}</span></strong>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-success btn-block"><i class="fa fa-cart-plus" aria-hidden="true"></i> Tambahkan ke keranjang</button>
                @csrf
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 mb-5">
        <div class="row mb-2">
            <div class="col-12">
                <hr>
                <h4 class="py-4">Produk Rekomendasi</h4>
            </div>
        </div>
        <div class="row">
            @foreach($recommendations as $recommendation)
            <div class="col-lg-2 col-md-2 col-sm-3 col-6" style="margin-bottom: 20px;">
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="{{route('product-detail', ['code' => $recommendation->code])}}" data-toggle="tooltip" title="{{$recommendation->name}}">
                            <div class="row">
                                <div class="col-12 w-100">
                                    <div class="catalog-image-grid medium @if($loop->index == 0) @endif" style="background-image: url('/img/{{$recommendation->url}}')">
                                        @if(!$recommendation->url) Tidak ada gambar @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">            
                        <h5 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:0;" data-toggle="tooltip" title="{{$recommendation->name}}"><a href="{{route('product-detail', ['code' => $recommendation->code])}}" style="text-decoration:none;">{{$recommendation->name}}</a></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 pb-3">Rp {{number_format($recommendation->price)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip();

    $('.catalog-image-grid.thumbnail').click(function(){
        currentImgId = $('.catalog-image-grid.thumbnail.img-active').attr('data-value')
        nextImgId = $(this).attr('data-value')

        // Displaying big preview
        $(`.img-display[data-value="${currentImgId}"]`).hide()
        $(`.img-display[data-value="${nextImgId}"]`).show()

        $('.catalog-image-grid.thumbnail.img-active').removeClass('img-active')
        $(this).addClass('img-active')
        
    })
</script>

@endsection