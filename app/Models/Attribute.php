<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_attribute');
    }

    public function values()
    {
        return $this->hasMany('App\Models\AttributeValues');
    }
}
