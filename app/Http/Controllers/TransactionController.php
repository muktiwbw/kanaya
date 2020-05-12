<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Auth;
use App\Transaction;
use App\TransactionDetail;
use App\Product;
use App\Schedule;
use App\TransactionLog;

class TransactionController extends Controller
{
    // public section
    public function catalog(){
        $products = Product::select('products.code', 'products.name', 'products.price', 'images.url')
                            ->leftJoin('image_product', 'image_product.product_id', '=', 'products.id')
                            ->leftJoin('images', 'image_product.image_id', '=', 'images.id')
                            ->whereIn('images.id', function($query){
                                $query->select(DB::raw('min(images.id) as img'))
                                ->from('images')
                                ->leftJoin('image_product', 'images.id', '=', 'image_product.image_id')
                                ->leftJoin('products', 'products.id', '=', 'image_product.product_id')
                                ->groupBy('products.code')
                                ->get();
                            })
                            ->groupBy('products.code', 'products.name', 'products.price', 'images.url')
                            ->get()
                            ->transform(function($el){
                                $el->sizes = DB::table('products')
                                                ->select('size', DB::raw('count(id) as stock'))
                                                ->where('code', $el->code)
                                                ->groupBy('size')
                                                ->orderBy('size', 'desc')
                                                ->get();
                                return $el;
                            });

        return view('products.catalog', ['products' => $products]);
    }

    public function detail($code){
        $product = Product::select('products.code', 'products.category', 'products.name', 'products.price', 'products.notes')
                            ->where('products.code', $code)
                            ->groupBy('products.code', 'products.category', 'products.name', 'products.price', 'products.notes')
                            ->first();

        $sizes = Product::select('products.size',
                        DB::raw('count(products.id) stock'))
                ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->where('products.code', $code)
                ->groupBy('products.size')
                ->orderBy('size', 'desc')
                ->get();
        
        $images = Product::where('products.code', $code)->first()->images;

        $product->setAttribute('sizes', $sizes);
        $product->setAttribute('images', $images);                            

        return view('products.detail', ['product' => $product]);
    }

    private function getLastItemQueue($transaction){
        return $transaction->products->transform(function($el) use ($transaction){
                                        $el->last_transaction_queue = Product::find($el->id)
                                                                    ->transactions()
                                                                    ->where('transactions.id', '<>', $transaction->id)
                                                                    ->orderBy('transactions.end_date', 'desc')
                                                                    ->first();
                                                
                                        return $el;
                                    })
                                    ->filter(function($queue){
                                        return $queue->last_transaction_queue; // This is a truth test
                                    });
    }

    private function getExpectedStartTime($item_queue){
        return $item_queue->count() > 0 ? $item_queue->map(function($el){
                                                            return $el->last_transaction_queue;
                                                        })
                                                    ->sortByDesc('end_date')
                                                    ->first()
                                                    ->end_date : null;
    }

    public function cart(){
        // If there's a transaction with status checkout, go to checkout page
        if(Auth::guard('customers')->user()->transactions()->whereIn('status', [1,2])->first()) return redirect()->route('checkout-page');
        
        // Show:
        // - Unfinished cart (must be only one)
        // - Creation date to now is not more than 1 day (status = 1)
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        if($transaction){
            $item_queue = $this->getLastItemQueue($transaction);
            
            $expected_start_time = $this->getExpectedStartTime($item_queue);
            // dd($item_queue);

            $products = Product::select('transactions.id as trx_id', 'products.code', 'products.name', 'products.price', 'images.url')
                                ->leftJoin('image_product', 'image_product.product_id', '=', 'products.id')
                                ->leftJoin('images', 'image_product.image_id', '=', 'images.id')
                                ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                                ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                                ->where('transactions.id', $transaction->id)
                                ->whereIn('images.id', function($query){
                                    $query->select(DB::raw('min(images.id) as img'))
                                            ->from('images')
                                            ->leftJoin('image_product', 'image_product.image_id', '=', 'images.id')
                                            ->leftJoin('products', 'image_product.product_id', '=', 'products.id')
                                            ->groupBy('products.code')
                                            ->get();
                                })
                                ->groupBy('transactions.id', 'products.code', 'products.name', 'products.price', 'images.url')
                                ->get()
                                ->transform(function($el){
                                    $el->sizes = Product::select(
                                                            'products.size', 
                                                            DB::raw('count(products.id) as quantity')
                                                        )
                                                        ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                                                        ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                                                        ->where('products.code', $el->code)
                                                        ->where('transactions.id', $el->trx_id)
                                                        ->groupBy('products.size')
                                                        ->get()
                                                        ->transform(function($em) use ($el){
                                                            $em->stock = Product::where('code', $el->code)
                                                                                ->where('size', $em->size)
                                                                                ->count();

                                                            $em->available = Product::where('code', $el->code)
                                                                                ->where('size', $em->size)
                                                                                ->whereDoesntHave('transactions', function($query){
                                                                                    $query->where('status', '<=', 3);
                                                                                })->count();
                                                            
                                                            return $em;
                                                        });
                                        
                                    $el->total = $el->sizes->map(function($en) use ($el){
                                                        return $en->quantity * $el->price;
                                                    })->sum();

                                    return $el;
                                });

            $transaction->setAttribute('products', $products);
            $transaction->setAttribute('item_queue', $item_queue);
            $transaction->setAttribute('expected_start_time', $expected_start_time);
        }

        // dd($transaction);

        return view('transactions.cart', ['transaction' => $transaction]);
    }

    public function addToCart($code, Request $request){ // Cart here is transaction in DB
        // If there's a transaction with status checkout, go to checkout page
        if(Auth::guard('customers')->user()->transactions()->whereIn('status', [1,2])->first()) return redirect()->route('checkout-page');

        // Get the user logged in now
        // Check for unfinished transaction (there can only be 1 maximum), use it if exists
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        // If there's none, create one
        if(!$transaction) {
            $transaction = Transaction::create([
                'trans_no' => Str::random(20),
                'customer_id' => Auth::guard('customers')->user()->id,
                'cart_expiration' => Carbon::now()->setTimezone('Asia/Jakarta')->addHours(2)->toDateTimeString()
            ]);
        } 
        // else if($transaction->products()->count() >= 5){
        //     return redirect()->route('cart')->withErrors(['message', 'Maksimal item pada keranjang belanja adalah 5 item.']);
        // }

        // Add the item to this transaction
        $quantity = $request->quantity && $request->quantity > 0 ? $request->quantity : 1;

        $products = $this->getAvailableProducts($quantity, ['code' => $code, 'size' => $request->size]);
            
        if($products->count() < $quantity){
            $wanting = $quantity - $products->count();

            $extraProducts = $this->getExtraProductsFromOccuringTransaction($transaction, $wanting, ['code' => $code, 'size' => $request->size]);
            
            foreach ($extraProducts as $extraProduct) {
                $products->push($extraProduct);
            }
        }
        
        // Assign product to transaction
        foreach($products as $product){
            $transaction->products()->save($product);
        }

        return redirect()->route('cart');
    }

    private function getAvailableProducts($quantity, $product){
        return Product::where('code', $product['code'])
                        ->where('size', $product['size'])
                        ->whereDoesntHave('transactions', function($query) {
                            $query->where('status', '<=', 3);
                        })
                        ->limit($quantity)
                        ->get();
    }

    private function getExtraProductsFromOccuringTransaction($transaction, $wanting, $product){
        return Product::select(
                            'products.id',
                            'products.code',
                            'products.name',
                            'products.price',
                            'products.size',
                            'products.notes',
                            'products.created_at',
                            'products.updated_at'
                        )
                    ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                    ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                    ->where('code', $product['code'])
                    ->where('size', $product['size'])
                    ->whereHas('transactions', function($query) use ($transaction) {
                        $query->where('transactions.id', '<>', $transaction->id);
                    })->get()
                    ->transform(function($el) use ($transaction){
                        $el->last_transaction = Product::find($el->id)
                                                    ->transactions()
                                                    ->where('transactions.id', '<>', $transaction->id)
                                                    ->orderBy('transactions.end_date', 'desc')
                                                    ->first()->end_date;
                                
                        return $el;
                    })
                    ->sortBy('last_transaction')
                    ->take($wanting)
                    ->map(function($el){
                        unset($el->last_transaction);

                        return $el;
                    });
    }

    public function updateCart(Request $request){
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();
        $transaction->update([
            'start_date' => date('Y-m-d',strtotime($request['dates']['start'])),
            'end_date' => date('Y-m-d',strtotime($request['dates']['end']))
        ]);

        return $transaction ? response()->json([
            'status' => 200,
            'data' => [
                'transaction' => $transaction
            ]
        ]) : response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan pada proses checkout!'
        ]);
    }

    private function getItemsInTransactionByCriteria($transaction, $shared, $criteria = null){
        return $transaction->products()->where('code', $criteria['code'])->where('size', $criteria['size'])
                                        ->get()
                                        ->transform(function($el) use ($transaction){
                                            $el->last_transaction_queue = Product::find($el->id)
                                                                        ->transactions()
                                                                        ->where('transactions.id', '<>', $transaction->id)
                                                                        ->orderBy('transactions.end_date', 'desc')
                                                                        ->first();
                                                    
                                            return $el;
                                        })
                                        ->filter(function($queue) use ($shared){
                                            return $shared ? $queue->last_transaction_queue : !$queue->last_transaction_queue; // This is a truth test
                                        });
    }

    public function getItemQueue(){
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        $items = $transaction->products->transform(function($el) use ($transaction){
                                            $el->transaction_queue = Product::find($el->id)
                                                                        ->transactions()
                                                                        ->where('transactions.id', '<>', $transaction->id)
                                                                        ->orderBy('transactions.end_date')
                                                                        ->get();
                                                    
                                            return $el;
                                        })
                                        ->filter(function($queue){
                                            return $queue->transaction_queue->count() > 0; // This is a truth test
                                        })->toArray();

        return response()->json([
            'status' => 200,
            'message' => 'All\'s good!',
            'data' => [
                'items' => array_values($items)
            ]
        ]);
    }

    public function updateCartItem($code, Request $request){
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        $newQty = $request->quantity;
        $oldQty = $transaction->products()->where('code', $code)
                                            ->where('size', $request->size)
                                            ->count();

        if($newQty > $oldQty){
            // Addition
            $addition = $newQty - $oldQty;

            $products = $this->getAvailableProducts($addition, ['code' => $code, 'size' => $request->size]);

            if($products->count() < $addition){
                $wanting = $addition - $products->count();
    
                $extraProducts = $this->getExtraProductsFromOccuringTransaction($transaction, $wanting, ['code' => $code, 'size' => $request->size]);
                
                foreach ($extraProducts as $extraProduct) {
                    $products->push($extraProduct);
                }
            }

            if($products){
                foreach($products as $product){
                    $transaction->products()->save($product);
                }
            }
        }elseif($newQty < $oldQty){
            // Deletion
            $deletion = $oldQty - $newQty;

            $products = $this->getItemsInTransactionByCriteria($transaction, true, ['code' => $code, 'size' => $request->size])
                                                ->transform(function($el){
                                                    $el->last_end_date = $el->last_transaction_queue->end_date;

                                                    return $el;
                                                })
                                                ->sortByDesc('last_end_date')
                                                ->take($deletion)
                                                ->map(function($el){
                                                    unset($el->last_transaction_queue);
                            
                                                    return $el;
                                                });

            if($products->count() < $deletion){
                $wanting = $deletion - $products;

                $extraDeletions = $this->getItemsInTransactionByCriteria($transaction, false, ['code' => $code, 'size' => $request->size])
                                                            ->take($wanting)
                                                            ->map(function($el){
                                                                unset($el->last_transaction);
                                        
                                                                return $el;
                                                            });

                foreach($extraDeletions as $extraDeletion){
                    $products->push($extraDeletion);
                }
            }

            if($products){
                foreach($products as $product){
                    $transaction->products()->detach($product->id);
                }
            }

            if($transaction->products()->count() <= 0){
                $transaction->delete();
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil memperbaruhi jumlah item!',
            'data' => [
                'expected_start_time' => $this->getExpectedStartTime($this->getLastItemQueue($transaction))
            ]
        ]);
    }

    public function deleteCart($code, Request $request){
        $transaction = Auth::guard('customers')->user()->transactions()->where('status', 0)->first();

        $products = $transaction->products()->where('code', $code)
                                            ->where('size', $request->size)
                                            ->get();
        
        if($products){
            foreach($products as $product){
                $transaction->products()->detach($product->id);
            }
        }

        if($transaction->products()->count() <= 0){
            $transaction->delete();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil menghapus item!'
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

        $transactions = Transaction::whereBetween('status', [1, 3])->orderBy('updated_at', 'desc')->paginate(10);

        return view('transactions.list', [
            'transactions' => $transactions
        ]);
    }

    public function transactionDetail($id){
        $transaction = Transaction::find($id);

        $products = Product::select('transactions.id as trx_id', 'products.code', 'products.name', 'products.price', 'images.url', 
                                    DB::raw('sum(products.price) as total_price'),
                                    DB::raw('count(products.id) as total_count'))
                            ->leftJoin('image_product', 'image_product.product_id', '=', 'products.id')
                            ->leftJoin('images', 'image_product.image_id', '=', 'images.id')
                            ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                            ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                            ->where('transactions.id', $transaction->id)
                            ->whereIn('images.id', function($query){
                                $query->select(DB::raw('min(images.id) as img'))
                                        ->from('images')
                                        ->leftJoin('image_product', 'image_product.image_id', '=', 'images.id')
                                        ->leftJoin('products', 'image_product.product_id', '=', 'products.id')
                                        ->groupBy('products.code')
                                        ->get();
                            })
                            ->groupBy('transactions.id', 'products.code', 'products.name', 'products.price', 'images.url')
                            ->get()
                            ->transform(function($el){
                                $el->sizes = Product::select('products.size', DB::raw('count(products.id) as quantity'))
                                                    ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                                                    ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                                                    ->where('products.code', $el->code)
                                                    ->where('transactions.id', $el->trx_id)
                                                    ->groupBy('products.size')
                                                    ->get();
                                return $el;
                            });

        $transaction->setAttribute('products', $products);

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
                $transaction->notes = 'Pembayaran diterima. Silakan untuk mengambil barang pesanan di gerai kami.';
                break;

            case 3:
                $transaction->notes = 'Barang telah diambil oleh peminjam.';
                break;

            case 4:
                // Copying transaction data to log table
                $items = Product::select('products.code', 'products.size', 'products.price',
                                        DB::raw('count(products.id) as quantity'))
                                ->leftJoin('transaction_details', 'transaction_details.product_id', '=', 'products.id')
                                ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                                ->where('transactions.id', $transaction->id)
                                ->groupBy('products.code', 'products.size', 'products.price')
                                ->get()
                                ->transform(function($el){
                                    $el->total = $el->quantity * $el->price;

                                    return $el;
                                });
                
                // dd($items);
                $transactionLogs = array_map(function($el){
                    return TransactionLog::create([
                        'code' => $el['code'],
                        'size' => $el['size'],
                        'price' => $el['price'],
                        'quantity' => $el['quantity'],
                        'total' => $el['total']
                    ]);
                }, $items->toArray());

                foreach ($transactionLogs as $transactionLog) {
                    $transaction->transactionLogs()->save($transactionLog);
                }

                foreach($transaction->products as $product){
                    $transaction->products()->detach($product->id);
                }

                $transaction->notes = 'Barang telah dikembalikan oleh peminjam.';
                break;
        }

        $transaction->status = $status;
        $transaction->save();

        return redirect()->route('admin-transaction-list');
    }

    public function transactionAllDone(){
        $transactions = Transaction::where('status', 4)->orderBy('updated_at', 'desc')->paginate(10);

        $transactions->getCollection()
                        ->transform(function($el){
                            $el->transactionLogs = $el->transactionLogs->transform(function($em){
                                $em->name = Product::select('name')->where('code', $em->code)->groupBy('name')->first()->name;

                                return $em;
                            });

                            return $el;
                        });

        return view('transactions.list-done', [
            'transactions' => $transactions
        ]);
    }

    public function customerTransactions(){
        $occurringTransactions = Auth::guard('customers')->user()->transactions()->whereBetween('status', [1, 3])->orderBy('created_at', 'desc')->get();
        $transactionLogs = Auth::guard('customers')->user()->transactions()->where('status', '>=', 4)->orderBy('created_at', 'desc')->get();

        $occurringTransactions->transform(function($el){
            $el->transactionLogs = $el->products()->select('products.name', 'products.size', DB::raw('count(products.id) quantity'), DB::raw('sum(products.price) total'))
                                        ->groupBy('products.name', 'products.size')
                                        ->get();

            $el->quantity = $el->transactionLogs->sum('quantity');
            $el->total = $el->transactionLogs->sum('total');

            return $el;
        });

        
        $transactionLogs->transform(function($el){
            $el->transactionLogs = $el->transactionLogs->transform(function($em){
                $em->name = Product::select('name')->where('code', $em->code)->groupBy('name')->first()->name;
                
                return $em;
            });

            $el->quantity = $el->transactionLogs->count('id');
            $el->total = $el->transactionLogs->sum('price');

            return $el;
        });

        $transactions = $occurringTransactions->merge($transactionLogs);

        // dd($transactions);
        
        return view('transactions.customer', [
            'transactions' => $transactions
        ]);
    }
}
