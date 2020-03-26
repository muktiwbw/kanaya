<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        // If there's a transaction with status checkout, go to checkout page
        if(Auth::guard('customers')->user()->transactions()->whereIn('status', [1,2])->first()) return redirect()->route('checkout-page');
        
        // Show:
        // - Unfinished cart (must be only one)
        // - Creation date to now is not more than 1 day (status = 1)
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        return view('transactions.cart', ['transaction' => $transaction]);
    }

    public function addToCart($id, Request $request){ // Cart here is transaction in DB
        // If there's a transaction with status checkout, go to checkout page
        if(Auth::guard('customers')->user()->transactions()->whereIn('status', [1,2])->first()) return redirect()->route('checkout-page');
        
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

    public function updateCart(Request $request){
        foreach ($request['data']['items'] as $item) {
            $transactionItem = TransactionDetail::find($item['id']);

            $transactionItem->update([
                'size' => $item['size'],
                'notes' => $item['notes'],
                'quantity' => $item['quantity'],
                'total' => $transactionItem->product->price * $item['quantity']
            ]);
        }

        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();
        $transaction->update([
            'start_date' => date('Y-m-d',strtotime($request['data']['dates']['start'])),
            'end_date' => date('Y-m-d',strtotime($request['data']['dates']['end']))
        ]);

        return $transaction ? response()->json([
            'status' => 200,
            'data' => [
                'transaction' => $transaction,
                'transactionDetails' => $transaction->transactionDetails()->with('product')->get()
            ]
        ]) : response()->json([
            'status' => 500,
            'message' => 'Error updating data'
        ]);
    }

    public function deleteCart($id){
        $transactionDetail = TransactionDetail::find($id)->delete();

        return $transactionDetail ? response()->json([
            'status' => 200,
            'data' => $transactionDetail
        ]) : response()->json([
            'status' => 500,
            'message' => 'Error deleting data'
        ]);
    }

    public function checkout(){
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        $transaction->status = 1;
        $transaction->save();

        return redirect()->route('checkout-page');

    }

    public function checkoutPage(){
        if(!Auth::guard('customers')->user()->transactions()->whereIn('status',[1,2])->first()) return redirect()->route('customer-transactions');
        
        $transaction = Auth::guard('customers')->user()->transactions()->whereIn('status',[1,2])->first();

        return view('transactions.checkout', ['transaction' => $transaction]);
    }

    public function checkoutReceipt(Request $request){
        $image = $request->file('image');
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 1)->first();

        try {
            $path = Storage::putFileAs(
                'receipts', $image, $transaction->id.'.'.$image->extension()
            );       

            $transaction->receipt = $path;
            $transaction->save();              
        } catch (\Throwable $error) {
            return redirect()->back()->with('message', $error);
        }

        return redirect()->route('checkout-page');
    }

    public function transactionAll(){
        // Inspect overdue transactions and change status to 5
        Transaction::where('status', 3)->where('end_date', '<', date('Y-m-d'))->update([
            'status' => 5
        ]);

        $transactions = Transaction::where('status', '<>', 0)->orderBy('updated_at', 'desc')->paginate(10);

        return view('transactions.list', [
            'transactions' => $transactions
        ]);
    }

    public function transactionDetail($id){
        $transaction = Transaction::find($id);

        return view('transactions.detail', [
            'transaction' => $transaction
        ]);
    }

    public function transactionStatus($id, $status, Request $request){
        $transaction = Transaction::find($id);
        
        switch ($status) {
            case 1:
                $transaction->notes = 'Pembayaran ditolak karena tidak sesuai. Mohon untuk mengunggah ulang bukti pembayaran.';
                $path = $transaction->receipt;
                $transaction->receipt = null;
                if(!Storage::delete($path) || !$transaction->save()) return redirect()->back()->with('message', 'Terjadi kesalahan menghapus gambar bukti pembayaran!');
                break;

            case 2:
                foreach($transaction->transactionDetails as $item){
                    $product = $item->product;
                    $product->rent += $item->quantity;
                    $product->available = $product->stock - $product->rent;
                    $product->save();
                }

                $transaction->notes = 'Pembayaran diterima. Silakan untuk mengambil barang pesanan di gerai kami.';
                break;

            case 3:
                $transaction->notes = 'Barang telah diambil oleh peminjam.';
                break;

            case 4:
                foreach($transaction->transactionDetails as $item){
                    $product = $item->product;
                    $product->rent -= $item->quantity;
                    $product->available = $product->stock - $product->rent;
                    $product->save();
                }

                $transaction->notes = 'Barang telah dikembalikan oleh peminjam.';
                break;
        }

        $transaction->status = $status;
        $transaction->save();

        return redirect()->route('admin-transaction-list');
    }

    public function customerTransactions(){
        $transactions = Auth::guard('customers')->user()->transactions()->where('status', '>=', 3)->orderBy('created_at', 'desc')->get();

        return view('transactions.customer', [
            'transactions' => $transactions
        ]);
    }
}
