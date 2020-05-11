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
        <table class="table table-hover table-bordered">
            <tr class="thead-dark">
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created Date</th>
            </tr>
            @foreach($customers as $customer)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$customer->name}}</td>
                <td>{{$customer->email}}</td>
                <td>{{$customer->phone}}</td>
                <td>{{$customer->address}}</td>
                <td>{{date_format($customer->created_at,"d M Y")}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-12 text-right">
        @if($customers->count() > 0)
            @if(!$customers->onFirstPage())<a href="{{$customers->url(1)}}" class="btn btn-dark"><<</a>
            <a href="{{$customers->previousPageUrl()}}" class="btn btn-dark"><</a>@endif
            @for($i=0;$i<$customers->lastPage();$i++)
                <a href="{{$customers->url($i+1)}}" class="btn @if($customers->currentPage() == $i+1) btn-dark @else btn-outline-dark @endif">{{$i+1}}</a>
            @endfor
            @if($customers->hasMorePages())<a href="{{$customers->nextPageUrl()}}" class="btn btn-dark">></a>
            <a href="{{$customers->url($customers->lastPage())}}" class="btn btn-dark">>></a>@endif
        @endif
    </div>
</div>
@endsection