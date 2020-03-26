<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['trans_no', 'notes', 'status', 'customer_id', 'user_id', 'start_date', 'end_date'];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function transactionDetails(){
        return $this->hasMany('App\TransactionDetail');
    }
}
