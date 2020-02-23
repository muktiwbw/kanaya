@extends('layouts.admin')

@section('title', 'Daftar Admin')

@section('admin-title', 'Daftar Admin')

@section('admin-content')

@component('components.user-navbar')
    @slot('admin', 'active')
    @slot('customer')
@endcomponent
<div class="row">
    @if(Auth::guard('users')->user()->status == 1)
    <div class="col-12 text-right pt-2 pb-2">
        <a class="btn btn-success" href="{{route('view-admin-create-user')}}">+ Tambahkan Admin</a>
    </div>
    @endif
    <div class="col-12">
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Role</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created Date</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>@if($user->status == 1) Superadmin @else Admin @endif</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone}}</td>
                <td>{{$user->address}}</td>
                <td>{{date_format($user->created_at,"d M Y")}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-12 text-right">
        @if($users->count() > 0)
            @if(!$users->onFirstPage())<a href="{{$users->url(1)}}" class="btn btn-dark"><<</a>
            <a href="{{$users->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
            @for($i=0;$i<$users->lastPage();$i++)
                <a href="{{$users->url($i+1)}}" class="btn @if($users->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
            @endfor
            @if($users->hasMorePages())<a href="{{$users->nextPageUrl()}}" class="btn btn-dark">></a>
            <a href="{{$users->url($users->lastPage())}}" class="btn btn-dark">>></a>@endif
        @endif
    </div>
</div>
@endsection