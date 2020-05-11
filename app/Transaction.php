<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['trans_no', 'notes', 'status', 'customer_id', 'user_id', 'start_date', 'end_date', 'cart_expiration'];

    protected $hidden = ['pivot'];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function products(){
        return $this->belongsToMany('App\Product', 'transaction_details');
    }

    public function transactionLogs(){
        return $this->hasMany('App\TransactionLog');
    }
}
