@extends('layouts.auth')

@section('title', 'Customer Login')

@section('auth-content')
<div class="row">
    <div class="col-6 offset-3 text-center" style="margin-top:15%;">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Customer Login</h1>
                <div class="row">
                    <div class="col-12">
                        <form class="text-left" action="{{route('login')}}" method="post">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <input class="btn btn-primary btn-block" type="submit" name="Submit">
                            <input type="hidden" name="_guard" value="customers">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection