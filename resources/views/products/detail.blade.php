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
<script>
    function preview(e){
        let elem = e.target
        
        // Remove img-active class in all img-thumbnails
        let thumbnails = Array.from(document.getElementsByClassName('img-thumbnail'))
        for(let i = 0; i < thumbnails.length; i++){
            thumbnails[i].classList.remove('img-active')
        }

        // Highlight thumbnail by giving img-active class
        elem.classList.add('img-active')

        // Add display none and Remove img-active in current display
        let currentDisplay = document.querySelector('.img-display.img-active')
        currentDisplay.classList.remove('img-active')
        currentDisplay.style.display = 'none'

        // Add img-active in selected image
        let id = elem.id.split('-')[2]
        let nextDisplay = document.getElementById(`img-display-${id}`)
        nextDisplay.classList.add('img-active')
        nextDisplay.style.display = 'block'

    }
</script>
<div class="row">
    <div class="col-4">
        <div class="row">
            <div class="col-12">
                @foreach($product->images as $image)
                <img id="img-display-{{$image->id}}" class="img img-fluid img-display @if($loop->index == 0) img-active @endif" @if($loop->index > 0) style="display:none;" @endif src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                @endforeach
            </div>
            <div class="col-12 pt-2">
                <div class="row">
                    @foreach($product->images as $image)
                    <div class="col-3">
                        <img id="img-thumbnail-{{$image->id}}" onclick="preview(event)" class="img img-fluid img-thumbnail @if($loop->index == 0) img-active @endif" src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
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
</div>
@endsection