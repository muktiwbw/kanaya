<?php

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Catalog
Route::get('catalog', 'TransactionController@catalog')->name('catalog');

// Product Detail
Route::get('product/{id}', 'TransactionController@detail')->name('product-detail');

// Halaman Login Customer
Route::middleware('guest:users', 'guest:customers')->get('login', 'AuthenticationController@loginView')->name('view-login');

// Handling Login Customer
Route::middleware('guest:users', 'guest:customers')->post('login', 'AuthenticationController@authenticate')->name('login');

// Halaman Registrasi Customer
Route::middleware('guest:users', 'guest:customers')->get('register', 'AuthenticationController@registerView')->name('view-register');

// Handling Registrasi Customer dan Admin
Route::post('register', 'AuthenticationController@register')->name('register');

// Logout User Admin dan Customer
Route::get('logout', 'AuthenticationController@logout')->name('logout');

// Admin Routes
Route::prefix('admin')->group(function(){
    // Halaman Login Admin
    Route::middleware('guest:users', 'guest:customers')->get('login', 'AuthenticationController@adminLoginView')->name('view-admin-login');
    
    // Kumpulan route yang hanya dapat diakses oleh User Admin
    Route::middleware('user')->group(function() {
        // Management Produk
        Route::prefix('products')->group(function(){
            // Tampilan Halaman Membuat Produk
            Route::get('new', 'ProductController@create')->name('admin-products-create');

            // Proses Penyimpanan Data Produk
            Route::post('new', 'ProductController@store')->name('admin-products-store');
            
            // Tampilan Semua Produk Dalam Database
            Route::get('/', 'ProductController@all')->name('admin-products-list');

            // Tampilan Edit Produk
            Route::get('{id}', 'ProductController@edit')->name('admin-products-edit');

            // Proses Update Data Produk
            Route::patch('{id}', 'ProductController@patch')->name('admin-products-patch');

            // Proses Menghapus Data Produk
            Route::delete('{id}', 'ProductController@delete')->name('admin-products-delete');
        });

        // Management Transaksi
        Route::prefix('transactions')->group(function(){
            // Tampilan Halaman List Transaksi
            Route::get('/', 'TransactionController@transactionAll')->name('admin-transaction-list');
            
            // Tampilan Halaman Transaksi
            Route::get('{id}', 'TransactionController@transactionDetail')->name('admin-transaction-detail');
            
            // Proses Transaksi (approve/reject/diambil/dikembalikan)
            Route::patch('{id}/{status}', 'TransactionController@transactionStatus')->name('admin-transaction-status');
        });
        
        // Management User Admin (Hanya dapat diakses oleh SUPER ADMIN)
        Route::middleware('superadmin')->group(function(){
            // Membuat User Admin
            Route::get('create-user', 'AuthenticationController@adminCreateUserView')->name('view-admin-create-user');

            // List User Admin
            Route::get('users', 'UserController@users')->name('admin-users-list');

            // List User Customer
            Route::get('customers', 'UserController@customers')->name('admin-customers-list');
        });
        
        // Management Data Customer
        Route::get('customers', 'UserController@customers')->name('admin-customers-list');
    });
});

// Customer Routes
Route::middleware('customer')->group(function(){
    // Customer Profile
    Route::get('profile', 'CustomerController@profile')->name('profile');

    // List Semua Transaksi Customer
    Route::get('transactions', 'TransactionController@customerTransactions')->name('customer-transactions');

    // Cart Routes
    Route::prefix('cart')->group(function(){
        // Halaman Cart
        Route::get('/', 'TransactionController@cart')->name('cart');

        // Edit notes ke cart
        Route::patch('addNote/{id}', 'TransactionController@addNoteToCart')->name('cart-add-note');
        
        // Mengubah cart status menjadi checkout
        Route::get('checkout', 'TransactionController@checkout')->name('cart-checkout');
        
        // Halaman Checkout
        Route::get('checkout-page', 'TransactionController@checkoutPage')->name('checkout-page');
        
        // Upload Bukti Pembayaran
        Route::post('checkout-receipt', 'TransactionController@checkoutReceipt')->name('checkout-receipt');
        
        // Menambahkan Item ke Cart
        Route::post('{id}', 'TransactionController@addToCart')->name('cart-add');
        
        // Mengupdate Item Cart
        Route::patch('{id}', 'TransactionController@updateCart')->name('cart-update');
        
        // Menghapus Item Cart
        Route::delete('{id}', 'TransactionController@deleteCart')->name('cart-delete');
    });
});