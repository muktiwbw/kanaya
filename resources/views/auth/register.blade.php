@extends('layouts.base')

@section('title', 'Registrasi Customer')

@section('content')
<h1>Registrasi Customer</h1>
<form action="{{route('register')}}" method="post">
    <input type="text" name="name" placeholder="Name" autofocus></br>
    <input type="text" name="email" placeholder="Email"></br>
    <input type="password" name="password" placeholder="Password"></br>
    <input type="password" name="confirm-password" placeholder="Confirm password"></br>
    <input type="text" name="phone" placeholder="Phone"></br>
    <input type="text" name="address" placeholder="Address"></br>
    <input type="hidden" name="_guard" value="customers">
    <input type="submit" name="Submit">
    @csrf
</form>
@endsection