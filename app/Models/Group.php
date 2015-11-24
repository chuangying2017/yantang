<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_group');
    }
}
