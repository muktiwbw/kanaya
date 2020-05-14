@extends('layouts.home')

@section('default-content')
<div class="row">
    <img class="cover-image" src="/img/assets/image/cover.jpg" alt="">
    <div class="col-12">
        <div class="row feature-grid">
            <div class="col-3 text-center" style="padding: 70px 0;">
                <h1 style="font-size: 5em;"><i class="fa fa-refresh" aria-hidden="true"></i></h1>
                <h3>30 Days Replacement</h3>        
            </div>
            <div class="col-3 text-center" style="padding: 70px 0;">
                <h1 style="font-size: 5em;"><i class="fa fa-gift" aria-hidden="true"></i></h1>
                <h3>Gift Card</h3>
            </div>
            <div class="col-3 text-center" style="padding: 70px 0;">
                <h1 style="font-size: 5em;"><i class="fa fa-lock" aria-hidden="true"></i></h1>
                <h3>Secure Payments</h3>
            </div>
            <div class="col-3 text-center" style="padding: 70px 0;">
                <h1 style="font-size: 5em;"><i class="fa fa-truck" aria-hidden="true"></i></h1>
                <h3>Free Shipping</h3>
            </div>
        </div>
    </div>
    <div class="col-12 display-few-category">
        <div class="row">
            @foreach($categories->take(3) as $category)
            <div class="col-4 few-item-container" style="background-image: url('/img/{{$category->products->first()->url}}')">
                <div class="row button-container">
                    <h2 class="text-center">{{ucwords(str_replace('-', ' ', $category->category))}}</h2>
                    <div style="height: 100%; width: 100%" class="text-center">
                        <a class="cb cb-outline see-more-button mt-4" href="#">LEBIH LANJUT</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-12 home-content">
        <div class="row">
            <div class="col-12 display-category-nav">
                <div class="row">
                    <div class="col-12 text-center">     
                        <h1 class="pb-4">Smart Clothing</h1>
                        <ul class="nav justify-content-center">
                            @foreach($categories as $category)
                            <li class="nav-item">
                                <a class="nav-link display-category-nav-link @if($loop->index == 0) active @endif" data-value="{{$category->category}}" href="#">{{ucwords(str_replace('-', ' ', $category->category))}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @foreach($categories as $category)
                <div class="row item-display" data-value="{{$category->category}}" @if($loop->index > 0) style="display:none;" @endif>
                    @foreach($category->products as $product)
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12 pb-4">
                                <div class="home-thumbnail" style="background-image: url('/img/{{$product->url}}');">
                                    <div class="button-container">
                                        <div style="height: 100%" class="d-flex justify-content-center align-items-center">
                                            <a class="cb cb-outline see-more-button" href="/product/{{$product->code}}">LEBIH LANJUT</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h3>{{ucwords(str_replace('-', ' ', $product->name))}}</h3>
                            </div>
                            <div class="col-12">
                                <h5>Rp {{number_format($product->price)}}</h5>
                            </div>
                        </div>
        
                    </div>
                    @endforeach
                    <div class="col-3 offset-md-{{($category->products->count() - 3) * -3}}">
                        <div class="d-flex justify-content-center align-items-center" style="height: 600px;">
                            <a class="cb cb-outline see-more-button" href="{{route('catalog')}}">CEK KATALOG</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-12 grid-gallery text-center">
                <h1 class="pb-5">Shop On Insta</h1>
                <div class="row">
                    @foreach($grids as $grid)
                    <div class="col-2">
                        <div class="row h-100 grid-background-container" style="background-image: url('/img/{{$grid->url}}');">
                            <div class="d-flex justify-content-center align-items-center button-container w-100">
                                <a class="cb cb-outline see-more-button" href="https://www.instagram.com/kanayakebaya/" target="_blank">CEK GALERI</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $('.display-category-nav-link').click(function(e){

        e.preventDefault()

        let currentCat = $(`.item-display[data-value="${$(`.display-category-nav-link.active`).attr('data-value')}"]`)
        let nextCat = $(`.item-display[data-value="${$(this).attr('data-value')}"]`)

        $(`.display-category-nav-link.active`).removeClass('active')
        $(this).addClass('active')

        currentCat.fadeOut()
        nextCat.fadeIn()

    })

</script>
@endsection