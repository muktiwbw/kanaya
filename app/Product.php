<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'price', 'notes', 'size', 'available'];

    protected $hidden = ['pivot'];

    public function images(){
        return $this->belongsToMany('App\Image');
    }

    public function transactions(){
        return $this->belongsToMany('App\Transaction', 'transaction_details');
    }
}
