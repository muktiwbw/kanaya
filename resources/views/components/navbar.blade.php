<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="@if(Auth::guard('customers')->check()) container @else container-fluid @endif">
        <a class="navbar-brand" href="{{route('home')}}">Kanaya Kebaya</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if(Auth::guard('customers')->check())
                <li>
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile')}}"><i class="fa fa-user" aria-hidden="true"></i> {{Auth::guard('customers')->user()->name}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Auth::guard('customers')->user()->transactions()->where('status',0)->first() && Auth::guard('customers')->user()->transactions()->where('status',0)->first()->transactionDetails()->count() > 0) text-danger @endif" href="{{route('cart')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{route('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
                @elseif(Auth::guard('users')->check())
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin-products-list')}}"><i class="fa fa-user" aria-hidden="true"></i> {{Auth::guard('users')->user()->name}}</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('register')}}">Register</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>