<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use App\Transaction;
use App\TransactionDetail;
use App\Product;

class TransactionController extends Controller
{
    // public section
    public function catalog(){
        return view('products.catalog', ['products' => Product::all()]);
    }

    public function detail($id){
        return view('products.detail', ['product' => Product::find($id)]);
    }

    public function cart(){
        // Show:
        // - Unfinished cart (must be only one)
        // - Creation date to now is not more than 1 day (status = 1)
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        return view('transactions.cart', ['transaction' => $transaction]);
    }

    public function addToCart($id, Request $request){ // Cart here is transaction in DB
        $product_id = $id;

        // Get the user logged in now
        // Check for unfinished transaction (there can only be 1 maximum), use it if exists
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        // If there's none, create one
        if(!$transaction) {
            $transaction = Transaction::create([
                'trans_no' => Str::random(20),
                'customer_id' => Auth::guard('customers')->user()->id
            ]);
        }

        // Add the item to this transaction
        $product = Product::find($product_id);

        if($product->available == 0) return redirect()->back()->with('message','Barang sedang tidak tersedia!');
        
        $existingItem = $transaction->transactionDetails()->whereHas('product', function($query) use ($product_id){
            $query->where('id', $product_id);
        })->first();

        $quantity = $existingItem ? $existingItem->quantity + 1 : 1;
        $total = $product->price * $quantity;

        if($existingItem){
            $existingItem->quantity = $quantity;
            $existingItem->total = $total;

            $existingItem->save();
        }else{
            TransactionDetail::create([
                'quantity' => $quantity,
                'total' => $total,
                'transaction_id' => $transaction->id,
                'product_id' => $product->id
            ]);
        }

        return redirect()->route('cart');
    }

    public function updateCart($id, Request $request){
        $transactionDetail = TransactionDetail::whereHas('product', function($query) use ($id){
            $query->where('id', $id);
        })->first();

        if($request->new_quantity == 0){
            $transactionDetail->delete();
        }else{
            $transactionDetail->quantity = $request->new_quantity;
            $transactionDetail->total = $transactionDetail->quantity * $transactionDetail->product->price;
    
            $transactionDetail->save();
        }

        return redirect()->route('cart');
    }

    public function deleteCart($id){
        $transactionDetail = TransactionDetail::whereHas('product', function($query) use ($id){
            $query->where('id', $id);
        })->first();

        $transactionDetail->delete();

        return redirect()->route('cart');
    }
}
