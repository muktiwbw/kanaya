@extends('layouts.home')

@section('default-content')
<div class="row">
    <div class="container-fluid cover-image"></div>
    <div class="col-12 text-center py-4">
        <ul class="nav justify-content-center">
            <li class="nav-item mx-2">
                <a class="cb cb-outline" href="{{route('catalog')}}">VIEW CATALOG</a>
            </li>
        </ul>
    </div>
</div>
@endsection