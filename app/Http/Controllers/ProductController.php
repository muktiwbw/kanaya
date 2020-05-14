<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Product;
use App\Image;

class ProductController extends Controller
{
    public function all(){
        $products = DB::table('products')
                    ->select('products.code', 
                            'products.category',
                            'products.name', 
                            'images.url', 
                            'products.price')
                    ->leftJoin('image_product', 'image_product.product_id', '=', 'products.id')
                    ->leftJoin('images', 'image_product.image_id', '=', 'images.id')
                    ->whereIn('images.id', function($query){
                        $query->select(DB::raw('min(images.id) as img'))
                        ->from('images')
                        ->join('image_product', 'images.id', '=', 'image_product.image_id')
                        ->join('products', 'products.id', '=', 'image_product.product_id')
                        ->groupBy('products.code')
                        ->get();
                    })
                    ->groupBy('products.code', 'products.category', 'products.name', 'images.url', 'products.price')
                    ->orderBy('products.code', 'desc')
                    ->paginate(10);    
                    
        $products->getCollection()->transform(function($el){
            $el->sizes = DB::table('products')
                    ->select('products.code',
                            'products.size', 
                            DB::raw('count(products.id) as stock'),
                            DB::raw('count(case when transactions.status is null or transactions.status >= 4 then products.id end) as available'),
                            DB::raw('count(case when transactions.status <= 3 then products.id end) as rent'))
                    ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                    ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->where('products.code', $el->code)
                    ->groupBy('products.code', 'products.size')
                    ->orderBy('products.code', 'desc')
                    ->get();

            return $el;
        });
        
        return view('products.all', [
            'products' => $products
        ]);
    }

    public function create(){
        $items = Product::groupBy('code')->count() > 0 ? intval(Product::select('code')->groupBy('code')->orderBy('code', 'desc')->first()->code) + 1 : 1;

        $categories = Product::select('category')->groupBy('category')->get(); 

        if($items < 10){
            $items = '0000'.$items;
        }elseif($items >= 10 and $items < 100){
            $items = '000'.$items;
        }elseif($items >= 100 and $items < 1000){
            $items = '00'.$items;
        }elseif($items >= 1000 and $items < 10000){
            $items = '0'.$items;
        }

        return view('products.new', ['code' => $items, 'categories' => $categories]);
    }

    public function store(Request $request){
        $products = $this->reusable_store([
            'stock' => $request->stock,
            'code' => $request->code,
            'name' => $request->name,
            'size' => $request->size,
            'category' => $request->new_category ? Str::slug($request->new_category, '-') : $request->category,
            'price' => $request->price,
            'notes' => $request->notes,
            'images' => $request->file('images')
        ]);

        return redirect()->route('admin-products-list');
    }

    private function reusable_store($product, $fileCount = 1, $storeImage = true){
        $products = [];

        $stock = $product['stock'];
        $code = $product['code'];
        $name = $product['name'];
        $size = $product['size'];
        $category = $product['category'];
        $price = $product['price'];
        $notes = $product['notes'];
        $imageFiles = $product['images'];

        try {
            for($i=0;$i<$stock;$i++){
                $products[] = Product::create([
                    'code' => $code,
                    'name' => $name,
                    'size' => $size,
                    'category' => $category,
                    'price' => $price,
                    'notes' => $notes,
                ]);
            }
        } catch (\Throwable $error) {
            return redirect()->back()->with('message', $error);
        }

        if($imageFiles && $storeImage && $stock > 0){
    
            foreach ($imageFiles as $imageFile) {
                try {
                    $path = Storage::putFileAs(
                        'products', $imageFile, $code.'-'.$name.'-'.$fileCount.'.'.$imageFile->extension()
                    );                        
                } catch (\Throwable $error) {
                    return redirect()->back()->with('message', $error);
                }
    
                $image = Image::create([
                    'name' => $name,
                    'url' => $path,
                    'path' => $path,
                ]);
    
                if(!$image) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan file gambar!');
    
                foreach ($products as $product) {
                    $product->images()->save($image);
                }
    
                $fileCount++;
            }
        }

        return $products;
    }

    public function edit($code){
        // $product = Product::where('code', $code)->first();
        $product = DB::table('products')
                    ->select('products.code', 
                            'products.category', 
                            'products.name', 
                            'products.price', 
                            'products.size', 
                            'products.notes',
                            DB::raw('count(products.id) as stock'),
                            DB::raw('count(case when transactions.status is null or transactions.status >= 4 then products.id end) as available'),
                            DB::raw('count(case when transactions.status <= 3 then products.id end) as rent'))
                    ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                    ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->where('products.code', $code)
                    ->groupBy('products.code', 'products.category', 'products.name', 'products.price', 'products.size', 'products.notes')
                    ->first();

        $product_code = $product->code;
        $images = Image::whereHas('products', function($query) use ($product_code){
            $query->where('code', $product_code);
        })->get();

        $sizes = [
            's' => Product::where('code', $code)->where('size', 's')->count(),
            'm' => Product::where('code', $code)->where('size', 'm')->count(),
            'l' => Product::where('code', $code)->where('size', 'l')->count(),
            'xl' => Product::where('code', $code)->where('size', 'xl')->count(),
        ];

        $categories = Product::select('category')->groupBy('category')->get(); 

        return view('products.edit', [
            'product' => $product,
            'images' => $images,
            'sizes' => $sizes,
            'categories' => $categories
        ]);
    }

    public function patch($code, Request $request){
        $products = Product::where('code', $code)->get();

        // Update basic data ==============================================================================================================
        foreach ($products as $product) {
            $product->name = $request->name;
            $product->price = $request->price;
            $product->notes = $request->notes;
            $product->category = $request->new_category ? Str::slug($request->new_category, '-') : $request->category;
            $product->save();
        }
        // Update basic data end ==========================================================================================================

        // Update images ==============================================================================================================
        // Delete files and db records
        // Get the first element as a representative
        foreach($products[0]->images()->whereIn('images.id', explode('|', $request->_images_selected))->get() as $image){
            if(!Storage::delete($image->path) || !$image->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus gambar!');
        }

        // Rename undeleted image record in db
        foreach($products[0]->images()->whereNotIn('images.id', explode('|', $request->_images_selected))->get() as $image){
            $image->name = $request->name;
            
            if(!$image->save()) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan data gambar!');
        }

        // Save new files
        $fileCount = $products[0]->images()->count() == 0 ? 1 : intval(explode('-', explode('.', $products[0]->images()->orderBy('id', 'desc')->first()->path)[0])[2]) + 1;
    
        if($request->file('images')){
            foreach($request->file('images') as $image){
                try {
                    $path = Storage::putFileAs(
                        'products', $image, $code.'-'.$request->name.'-'.$fileCount.'.'.$image->extension()
                    );
                } catch (\Throwable $error) {
                    return redirect()->back()->with('message', $error);
                }   
                
                $newImage = Image::create([
                    'name' => $request->name,
                    'url' => $path,
                    'path' => $path,
                ]);

                if(!$newImage) return redirect()->back()->with('message', 'Terjadi kesalahan menyimpan data gambar!');

                foreach($products as $product){
                    $product->images()->save($newImage);
                }
    
                $fileCount++;
            }
        }
        // Update images end ==========================================================================================================

        // Managing stocks ============================================================================================================
        $currentSizeStock = Product::where('code', $request->code)->where('size', $request->size)->count();

        if($request->stock > $currentSizeStock){
            // Add
            $addition = $request->stock - $currentSizeStock;
            $newProducts = $this->reusable_store(
                [
                    'stock' => $addition,
                    'code' => $request->code,
                    'name' => $request->name,
                    'size' => $request->size,
                    'category' => $request->new_category ? Str::slug($request->new_category, '-') : $request->category,
                    'price' => $request->price,
                    'notes' => $request->notes,
                    'images' => null,
                ],
                null,
                false
            );

            $oldProductImages = Product::where('code', $request->code)->whereNotIn('id', array_map(function($el){
                return $el->id;
            }, $newProducts))->first()->images;

            foreach ($newProducts as $newProduct) {
                foreach ($oldProductImages as $oldProductImage) {
                    $newProduct->images()->save($oldProductImage);
                }
            }
        }elseif($request->stock < $currentSizeStock){
            // Delete
            $deletion = $currentSizeStock - $request->stock;
            $deletedStock = Product::where('code', $request->code)
                                    ->where('size', $request->size)
                                    ->whereDoesntHave('transactions', function($query){
                                        $query->where('status', '<=', 3);
                                    })
                                    ->orderBy('id', 'desc')
                                    ->limit($deletion)
                                    ->delete();
        }
        // Managing stocks end ========================================================================================================

        return redirect()->back()->with('message', 'Data tersimpan!');
    }

    public function delete($code){
        $products = Product::where('code', $code);

        foreach($products->get()[0]->images as $image){
            if(!Storage::delete($image->path) || !$image->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus gambar!');
        }

        if(!$products->delete()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus data produk!');

        return redirect()->route('admin-products-list')->with('message', 'Product berhasil dihapus!');
    }

}
