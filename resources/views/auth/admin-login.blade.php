@extends('layouts.auth')

@section('title', 'Admin Login')

@section('auth-content')
<div class="row">
    <div class="col-6 offset-3 text-center" style="margin-top:15%;">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Admin Login</h1>
                <div class="row">
                    <div class="col-12">
                        <form class="text-left" action="{{route('login')}}" method="post">
                            <div class="form-group">
                                <label for="email">Username</label>
                                <input type="text" name="email" class="form-control" id="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <input class="btn btn-primary btn-block" type="submit" name="Submit">
                            <input type="hidden" name="_guard" value="users">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection