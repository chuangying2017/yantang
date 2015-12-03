<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'category_attribute');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
