@extends('layouts.auth')

@section('title', 'Tambah Admin')

@section('auth-content')
<div class="row">
    <div class="col-6 offset-3 text-center" style="margin-top:15vh;">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Tambah Admin</h1>
                <div class="row">
                    <div class="col-12">
                        <form class="text-left" action="{{route('register')}}" method="post">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" id="name" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="email">Username</label>
                                <input type="text" name="email" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" id="phone">
                            </div>
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <input type="text" name="address" class="form-control" id="address">
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