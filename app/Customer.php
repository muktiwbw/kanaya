<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'phone', 'address'];

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
}
