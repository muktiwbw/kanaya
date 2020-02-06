<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'price', 'notes', 'stock', 'rent', 'available'];

    public function images(){
        return $this->hasMany('App\Image');
    }

    public function transactionDetails(){
        return $this->hasMany('App\TransactionDetail');
    }
}
