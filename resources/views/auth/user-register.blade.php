@extends('layouts.base')

@section('title', 'Tambah Admin')

@section('content')
<h1>Tambah Admin</h1>
<form action="{{route('register')}}" method="post">
    <input type="text" name="name" placeholder="Name" autofocus></br>
    <input type="text" name="email" placeholder="Email"></br>
    <input type="password" name="password" placeholder="Password"></br>
    <input type="text" name="phone" placeholder="Phone"></br>
    <input type="text" name="address" placeholder="Address"></br>
    <input type="submit" name="Submit">
    <input type="hidden" name="_guard" value="users">
    @csrf
</form>
@endsection