<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Kanaya Kebaya</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li>
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-user" aria-hidden="true"></i> {{Auth::guard('customers')->user()->name}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('cart')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</a>
                </li>
            </ul>
        </div>
    </div>
</nav>