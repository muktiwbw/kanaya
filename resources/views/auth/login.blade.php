@extends('layouts.auth')

@section('title', 'Customer Login')

@section('auth-content')
<style>
    .card-body {
        transition: all .5s;
    }
    
    .card-body.dark, .card-body.dark a {
        background-color: rgb(100,100,100);
        color: var(--font-white)
    }
</style>
<div class="row">
    <div class="col-6 offset-3 text-center" style="margin-top:15vh;">
        <div class="card">
            <div class="card-body">
                <h1 id="card-title" class="card-title">Customer Login</h1>
                <div class="row">
                    <div class="col-12 mb-3">
                        <form id="customer-form" class="text-left active" action="{{route('login')}}" method="post">
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
                        <form id="admin-form" class="text-left" action="{{route('login')}}" method="post" style="display:none;">
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
                    <div class="col-12 text-right">
                        <a id="user-toggle" href="#" user="customer" style="text-decoration:none"><span>Login sebagai admin</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const user_toggle = $('#user-toggle')

    user_toggle.click(function(){
        if($(this).attr('user') == 'customer') {
            $(this).attr('user', 'admin')
            $(this).html('Login sebagai customer')
            $('#customer-form').hide()
            $('#admin-form').show()
            $('.card-body').addClass('dark')
            $('#card-title').html('Admin Login')
        } else{
            $(this).attr('user', 'customer')
            $(this).html('Login sebagai admin')
            $('#admin-form').hide()
            $('.card-body').removeClass('dark')
            $('#customer-form').show()
            $('#card-title').html('Customer Login')
        }
    })
</script>
@endsection