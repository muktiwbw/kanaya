@extends('layouts.admin')

@section('title', 'Daftar Admin')

@section('admin-title', 'Daftar Admin')

@section('admin-content')

@component('components.user-navbar')
    @slot('admin', 'active')
    @slot('customer')
@endcomponent
<div class="row">
    <div class="col-12">
    </div>
</div>
@endsection