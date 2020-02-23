@extends('layouts.base')

@section('content')
<div class="container @if(!Auth::guard('customers')->check() && !Auth::guard('users')->check()) pt-4 @endif">
    <div class="card">
        <div class="card-body">
            @yield('default-content')
        </div>
    </div>
</div>
@endsection