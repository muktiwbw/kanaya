@extends('layouts.admin')

@section('title', 'Daftar Customer')

@section('admin-title', 'Daftar Customer')

@section('admin-content')

@component('components.user-navbar')
    @slot('admin')
    @slot('customer', 'active')
@endcomponent
<div class="row">
    <div class="col-12">
    </div>
</div>
@endsection