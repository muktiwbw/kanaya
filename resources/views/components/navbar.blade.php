<nav class="navbar navbar-expand-lg navbar-light bg-light py-2">
    <div class="container">
        <a class="navbar-brand" href="{{route('home')}}">
            <img src="/img/assets/logo/kanaya.png" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if(Auth::guard('users')->check() || Auth::guard('customers')->check())
                <li class="mx-1">
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="{{route('catalog')}}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Katalog</a>
                </li>
                @if(Auth::guard('customers')->check())
                <li class="nav-item mx-1">
                    <a class="nav-link @if(Auth::guard('customers')->user()->transactions()->where('status',0)->first() && Auth::guard('customers')->user()->transactions()->where('status',0)->first()->products()->count() > 0) text-danger @endif" href="{{route('cart')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Keranjang</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="{{route('customer-transactions')}}"><i class="fa fa-clock-o" aria-hidden="true"></i> Riwayat</a>
                </li>
                @endif
                <li class="nav-item mx-1">
                    <a class="nav-link" href="{{Auth::guard('customers')->check() ? route('profile') : route('admin-products-list')}}"><i class="fa fa-user" aria-hidden="true"></i> {{Auth::guard('customers')->check() ? explode(' ', Auth::guard('customers')->user()->name)[0] : explode(' ', Auth::guard('users')->user()->name)[0]}}</a>
                </li>
                <li class="nav-item ml-1">
                    <a class="nav-link text-danger" href="{{route('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Keluar</a>
                </li>
                @else
                <li class="nav-item mx-1">
                    <a class="nav-link" href="{{route('login')}}">Masuk</a>
                </li>
                <li class="nav-item ml-1">
                    <a class="nav-link" href="{{route('register')}}">Daftar</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>