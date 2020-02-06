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
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->whereRaw('timestampdiff(hour, created_at, CURRENT_TIMESTAMP) <= 24')->first();

        return view('transactions.cart', ['transaction' => $transaction]);
    }

    public function addToCart(Request $request){ // Cart here is transaction in DB
        // Get the user logged in now
        // Check for unfinished transaction (there can only be 1 maximum), use it if exists
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->whereRaw('timestampdiff(hour, created_at, CURRENT_TIMESTAMP) <= 24')->first();

        // If there's none, create one
        if(!$transaction) {
            $transaction = Transaction::create([
                'trans_no' => Str::random(20),
                'customer_id' => Auth::guard('customers')->user()->id
            ]);
        }

        // Add the item to this transaction
        $product = Product::find($request->_product_id);

        if($product->available == 0) return redirect()->back()->with('message','Barang sedang tidak tersedia!');

        $quantity = !$request->_quantity ? 1 : $request->_quantity;
        $total = $product->price * $quantity;

        TransactionDetail::create([
            'quantity' => $quantity,
            'total' => $total,
            'transaction_id' => $transaction->id,
            'product_id' => $product->id
        ]);

        return redirect()->route('catalog');

    }
}
