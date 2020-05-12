<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="@if(Auth::guard('customers')->check() || Auth::check()) container @else container-fluid @endif">
        <a class="navbar-brand" href="{{route('home')}}">
            <img src="img/assets/logo/kanaya.png" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if(Auth::check())
                <li class="mx-2">
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{route('catalog')}}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Catalog</a>
                </li>
                @if(!Auth::guard('users')->check())
                <li class="nav-item mx-2">
                    <a class="nav-link @if(Auth::guard('customers')->user()->transactions()->where('status',0)->first() && Auth::guard('customers')->user()->transactions()->where('status',0)->first()->products()->count() > 0) text-danger @endif" href="{{route('cart')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</a>
                </li>
                @endif
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{route('profile')}}"><i class="fa fa-user" aria-hidden="true"></i> {{Auth::user()->name}}</a>
                </li>
                <li class="nav-item ml-2">
                    <a class="nav-link text-danger" href="{{route('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
                @else
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{route('login')}}">Login</a>
                </li>
                <li class="nav-item ml-2">
                    <a class="nav-link" href="{{route('register')}}">Register</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>