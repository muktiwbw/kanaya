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
                <form action="{{route('cart-add', ['id' => $product->id])}}" method="post">
                <button type="submit" class="btn btn-outline-success btn-block"><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to cart!</button>
                @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection