<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['name', 'url', 'path'];

    protected $hidden = ['pivot'];

    public function products(){
        return $this->belongsToMany('App\Product');
    }
}
