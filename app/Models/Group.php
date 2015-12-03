<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_group');
    }
}
