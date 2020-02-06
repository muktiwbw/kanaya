<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Product;
use App\Image;

class ProductController extends Controller
{
    public function all(){
        $products = Product::orderBy('updated_at', 'desc')->paginate(5);
        return view('products.all', [
            'products' => $products
        ]);
    }

    public function create(){
        $items = Product::count() > 0 ? Product::orderBy('id', 'desc')->first()->id + 1 : 1;

        if($items < 10){
            $items = '0000'.$items;
        }elseif($items >= 10 and $items < 100){
            $items = '000'.$items;
        }elseif($items >= 100 and $items < 1000){
            $items = '00'.$items;
        }elseif($items >= 1000 and $items < 10000){
            $items = '0'.$items;
        }

        return view('products.new', ['code' => $items]);
    }

    public function store(Request $request){
        $product;

        try {
            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'price' => $request->price,
                'notes' => $request->notes,
                'stock' => $request->stock,
                'rent' => $request->rent,
                'available' => $request->stock - $request->rent,
            ]);
        } catch (\Throwable $error) {
            return redirect()->back()->with('message', $error);
        }

        $fileCount = 1;

        foreach ($request->file('images') as $image) {
            try {
                $path = Storage::putFileAs(
                    'products', $image, $product->id.'-'.$fileCount.'.'.$image->extension()
                );                        
            } catch (\Throwable $error) {
                return redirect()->back()->with('message', $error);
            }

            if(!Image::create([
                'name' => $product->name,
                'url' => $path,
                'path' => $path,
                'product_id' => $product->id,
            ])) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan file gambar!');;

            $fileCount++;
        }

        return redirect()->route('admin-products-list');
    }

    public function edit($id){
        $product = Product::find($id);

        return view('products.edit', [
            'product' => $product
        ]);
    }

    public function patch($id, Request $request){
        $product = Product::find($id);

        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->notes = $request->notes;
        $product->stock = $request->stock;
        $product->rent = $request->rent;
        $product->available = $request->stock - $request->rent;

        foreach($product->images()->whereIn('id', explode('|', $request->_images_selected))->get() as $image){
            if(!Storage::delete($image->path) || !$image->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus gambar!');
        }

        foreach($product->images()->whereNotIn('id', explode('|', $request->_images_selected))->get() as $image){
            $image->name = $request->name;
            
            if(!$image->save()) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan data gambar!');
        }

        if($request->file('images')){
            $fileCount = $product->images()->count() == 0 ? 1 : intval(explode('-', explode('.', $product->images()->orderBy('id', 'desc')->first()->path)[0])[1]) + 1;
            
            foreach($request->file('images') as $image){
                try {
                    $path = Storage::putFileAs(
                        'products', $image, $product->id.'-'.$fileCount.'.'.$image->extension()
                    );
                } catch (\Throwable $error) {
                    return redirect()->back()->with('message', $error);
                }   
                
                if(!Image::create([
                    'name' => $request->name,
                    'url' => $path,
                    'path' => $path,
                    'product_id' => $product->id,
                ])) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan data gambar!');
    
                $fileCount++;
            }
        }

        if(!$product->save()) return redirect()->back()->with('message', 'Terjadi kesalahan!');

        return redirect()->back()->with('message', 'Data tersimpan!');
    }

    public function delete($id){
        $product = Product::find($id);

        foreach($product->images as $image){
            if(!Storage::delete($image->path) || !$image->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus gambar!');
        }

        if(!$product->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus data gambar!');

        return redirect()->route('admin-products-list')->with('message', 'Product berhasil dihapus!');
    }

    public function catalog(){
        return view('products.catalog', ['products' => Product::all()]);
    }

}
