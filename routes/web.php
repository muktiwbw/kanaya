<?php

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->group(function(){
    // Login User Admin
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
        
        // Management User Admin (Hanya dapat diakses oleh SUPER ADMIN)
        Route::middleware('superadmin')->group(function(){

            // Membuat User Admin
            Route::get('create-user', 'AuthenticationController@adminCreateUserView')->name('view-admin-create-user');

            // List User Admin
            Route::get('users', 'UserController@users')->name('admin-users-list');
        });
        
        // Management Data Customer
        Route::get('customers', 'UserController@customers')->name('admin-customers-list');
    });
});

// Logout User Admin dan Customer
Route::get('logout', 'AuthenticationController@logout')->name('logout');

// In Progress
// ============================================================================================================================================
// ============================================================================================================================================
// ============================================================================================================================================
// ============================================================================================================================================

// Catalog
Route::get('catalog', 'TransactionController@catalog')->name('catalog');

Route::prefix('product')->group(function(){
    Route::get('{id}', 'TransactionController@detail')->name('product-detail');
});

Route::middleware('customer')->group(function(){

    Route::get('profile', 'CustomerController@profile')->name('profile');

    Route::prefix('cart')->group(function(){

        Route::get('/', 'TransactionController@cart')->name('cart');
        
        Route::get('checkout', 'TransactionController@checkout')->name('cart-checkout');
        
        Route::post('{id}', 'TransactionController@addToCart')->name('cart-add');

        Route::patch('{id}', 'TransactionController@updateCart')->name('cart-update');

        Route::delete('{id}', 'TransactionController@deleteCart')->name('cart-delete');

    });

});

Route::middleware('guest:users', 'guest:customers')->post('login', 'AuthenticationController@authenticate')->name('login');

Route::middleware('guest:users', 'guest:customers')->get('register', 'AuthenticationController@registerView')->name('view-register');
Route::post('register', 'AuthenticationController@register')->name('register');

Route::middleware('guest:users', 'guest:customers')->get('login', 'AuthenticationController@loginView')->name('view-login');

/**
 * Todo:
 * 1. Konfirmasi dari sisi admin
 * 2. Pengembalian
 */

