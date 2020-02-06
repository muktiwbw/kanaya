<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['quantity', 'total', 'transaction_id', 'product_id'];

    public function transaction(){
        return $this->belongsTo('App\Transaction');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
