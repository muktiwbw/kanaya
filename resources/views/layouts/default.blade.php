@extends('layouts.base')

@section('content')
<div class="container content-section @if(!Auth::guard('customers')->check() && !Auth::guard('users')->check()) pt-4 @endif">
    @yield('default-content')
</div>
@endsection