@extends('layouts.default')

@section('title', Auth::guard('customers')->user()->name)

@section('default-content')
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-user" aria-hidden="true"></i> {{Auth::guard('customers')->user()->name}}</h1>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <strong>Nama</strong>
    </div>
    <div class="col-9">
        {{Auth::guard('customers')->user()->name}}
    </div>
</div>
<div class="row">
    <div class="col-3">
        <strong>Email</strong>
    </div>
    <div class="col-9">
        {{Auth::guard('customers')->user()->email}}
    </div>
</div>
<div class="row">
    <div class="col-3">
        <strong>Alamat</strong>
    </div>
    <div class="col-9">
        {{Auth::guard('customers')->user()->address}}
    </div>
</div>
<div class="row">
    <div class="col-3">
        <strong>No. Telepon</strong>
    </div>
    <div class="col-9">
        {{Auth::guard('customers')->user()->phone}}
    </div>
</div>
@endsection