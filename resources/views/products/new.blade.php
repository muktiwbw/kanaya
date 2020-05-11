@extends('layouts.admin')

@section('title', 'New Product')

@section('admin-title', 'Tambahkan Produk')

@section('admin-content')
<form action="{{route('admin-products-store')}}" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="code">Kode Produk</label>
        <input type="text" name="code" class="form-control" id="code" value="{{$code}}" readonly>
    </div>
    <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" name="name" class="form-control" id="name" autofocus>
    </div>
    <div class="form-group">
        <label for="size">Ukuran</label>
        <select name="size" id="size" class="form-control">
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
        </select>
    </div>
    <div class="form-group">
        <label for="price">Harga</label>
        <input type="number" name="price" class="form-control" id="price">
    </div>
    <div class="form-group">
        <label for="notes">Note</label>
        <textarea name="notes" class="form-control" id="notes"></textarea>
    </div>
    <div class="form-group">
        <label for="stock">Stok</label>
        <input type="number" name="stock" class="form-control" id="stock" value="0">
    </div>
    <div class="form-group">
        <label for="images">Tambahkan Gambar</label>
        <input type="file" class="form-control" id="images" name="images[]" multiple>
    </div>
    <input class="btn btn-success btn-block" type="submit" name="submit" value="Tambah Produk">
    @csrf
</form>
@endsection