@extends('layouts.default')

@section('title', $product->name)

@section('default-content')
<div class="row pb-4">
    <div class="col-12">
        <a href="{{route('admin-products-list')}}"><i class="fa fa-chevron-left" aria-hidden="true"></i> Kembali</a>
    </div>
</div>
@page_title(['title' => 'Ubah Detail Produk'])@endpage_title
<div class="row">
    <div class="col-4">
        <div class="alert alert-primary" role="alert">
            <h5><strong><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Bantuan</strong></h5>
            <p>Klik untuk memilih gambar yang ingin dihapus.</p>
            <p>Untuk menambahkan gambar klik pada menu Tambahkan Gambar di bawah.</p>
        </div>
        <div class="grid-row">
            <div class="grid-column">
                @foreach($images as $image)
                    @if(($loop->index + 1) % 2 == 1)
                    <img id="image-{{$image->id}}" class="img img-thumbnail display-images" onclick="highlight(event)" style="width:100%" src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                    @endif
                @endforeach
            </div>
            <div class="grid-column">
                @foreach($images as $image)
                    @if(($loop->index + 1) % 2 == 0)
                    <img id="image-{{$image->id}}" class="img img-thumbnail display-images" onclick="highlight(event)" style="width:100%" src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="row">
            <div class="col-6"><h3>{{$product->name}}</h3></div>
            <div class="col-6 text-right">
                <form action="{{route('admin-products-delete', ['code' => $product->code])}}" method="post">
                    <button type="submit" class="btn btn-danger">Hapus Produk</button>
                    @method('delete')
                    @csrf
                </form>
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin-products-patch', ['code' => $product->code])}}" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code">Kode Produk</label>
                        <input type="text" name="code" class="form-control" id="code" value="{{$product->code}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{$product->name}}">
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" name="price" class="form-control" id="price" value="{{$product->price}}">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" class="form-control" id="notes">{{$product->notes}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="stock">Manajemen Stock</label>
                        <div class="row">
                            <div class="col-3">
                                <select name="size" id="size" class="form-control">
                                    <option class="size-counter" value="s" data-value="{{$sizes['s']}}" selected>S</option>
                                    <option class="size-counter" value="m" data-value="{{$sizes['m']}}">M</option>
                                    <option class="size-counter" value="l" data-value="{{$sizes['l']}}">L</option>
                                    <option class="size-counter" value="xl" data-value="{{$sizes['xl']}}">XL</option>
                                </select>
                            </div>
                            <div class="col-9">
                                <input type="number" name="stock" class="form-control" id="stock" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <div class="row">
                            <div class="col-4">
                                <select name="category" class="form-control" @if($categories->count() == 0) disabled @endif>
                                    @foreach($categories as $category)
                                    <option value="{{$category->category}}" @if($product->category && $category->category == $product->category) selected @endif>{{ucwords(str_replace('-', ' ', $category->category))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="new_category" placeholder="Tambahkan kategori baru">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="available">Tersedia</label>
                        <input type="number" name="available" class="form-control" id="available" value="{{$product->available}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="rent">Dipinjam</label>
                        <input type="number" name="rent" class="form-control" id="rent" value="{{$product->rent}}" disabled>
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
    $('#stock').val($('#size').children("option:selected").attr('data-value'))

    function highlight(e){
        let elem = e.target
        
        // Highlight image
        if(elem.style.borderColor != 'red'){
            elem.style.borderStyle = 'solid'
            elem.style.borderColor = 'red'
            elem.style.borderWidth = '6px'
            elem.setAttribute('selected', '')
        } else {
            elem.style.borderColor = '#464646'
            elem.style.borderWidth = '3px'
            elem.removeAttribute('selected', '')
        }

        // Submit images ID
        imagesSelected.value = Array.from(document.querySelectorAll('img.display-images[selected]'), el => el.id.split('-')[1]).join('|')

    }

    $('#size').change(function(){
        $('#stock').val($(this).children("option:selected").attr('data-value'))
    })

</script>
@endsection