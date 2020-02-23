@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12" style="margin-top:15px;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">Hello, {{Auth::guard('users')->user()->name}}!</h2>
                                    <nav class="row">
                                        <div class="col-12"><a href="{{route('admin-products-list')}}" class="text-left btn btn-block btn-outline-dark" style="border:none;">Product</a></div>
                                        @if(Auth::guard('users')->user()->status == 1)
                                        <div class="col-12"><a href="{{route('admin-users-list')}}" class="text-left btn btn-block btn-outline-dark" style="border:none;">Users</a></div>
                                        @endif
                                        <div class="col-12"><a href="{{route('admin-transaction-list')}}" class="text-left btn btn-block btn-outline-dark" style="border:none;">Transactions</a></div>
                                        <div class="col-12"><a href="{{route('home')}}" class="text-left btn btn-block btn-outline-primary" style="border:none; margin-top:30px">Home</a></div>
                                        <div class="col-12"><a href="{{route('logout')}}" class="text-left btn btn-block btn-outline-danger" style="border:none;">Logout</a></div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card">
                                <div class="card-header">
                                    <h2>@yield('admin-title')</h2>
                                </div>
                                <div class="card-body">
                                    @yield('admin-content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection