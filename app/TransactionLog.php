<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = ['code', 'size', 'price', 'quantity', 'total', 'transaction_id'];

    public function transaction(){
        return $this->belongsTo('App\Transaction');
    }
}
