@extends('layouts.admin')

@section('title', $product->name)

@section('admin-title', $product->name)

@section('admin-content')
<div class="row">
    <div class="col-4">
        <div class="row">
            <div class="col-12" style="margin-bottom:10px;"><h6>Klik untuk memilih gambar yang ingin dihapus</h6></div>
            <div class="col-12" style="margin-bottom:15px;">
                <div class="row">
                @foreach($product->images as $image)
                <div class="col-6" style="margin-bottom:15px;">
                    <img onclick="highlight(event)" style="border-style: none;" id="image-{{$image->id}}" width="200" class="display-images" src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="row">
            <div class="col-6">
                <h3>Ubah Detail Produk</h3>
            </div>
            <div class="col-6 text-right">
                <form action="{{route('admin-products-delete', ['id' => $product->id])}}" method="post">
                    <button type="submit" class="btn btn-danger">Hapus Produk</button>
                    @method('delete')
                    @csrf
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin-products-patch', ['id' => $product->id])}}" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code">Product Code</label>
                        <input type="text" name="code" class="form-control" id="code" value="{{$product->code}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{$product->name}}">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" class="form-control" id="price" value="{{$product->price}}">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" class="form-control" id="notes">{{$product->notes}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" class="form-control" id="stock" value="{{$product->stock}}">
                    </div>
                    <div class="form-group">
                        <label for="rent">Dipinjam</label>
                        <input type="number" name="rent" class="form-control" id="rent" value="{{$product->rent}}">
                    </div>
                    <div class="form-group">
                        <label for="images">Tambahkan Gambar</label>
                        <input type="file" name="images[]" class="form-control" id="images" multiple>
                    </div>
                    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Update">
                    <input id="images-selected" type="hidden" name="_images_selected" value="">
                    @method('PATCH')
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const imagesSelected = document.getElementById('images-selected')

    function highlight(e){
        let elem = e.target
        
        // Highlight image
        if(elem.style.borderStyle == 'none'){
            elem.style.borderStyle = 'solid'
            elem.style.borderColor = 'red'
            elem.style.borderWidth = '5px'
            elem.setAttribute('selected', '')
        } else {
            elem.style.borderStyle = 'none'
            elem.removeAttribute('selected', '')
        }

        // Submit images ID
        imagesSelected.value = Array.from(document.querySelectorAll('img.display-images[selected]'), el => el.id.split('-')[1]).join('|')

    }
</script>
@endsection