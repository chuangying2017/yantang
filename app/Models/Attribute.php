<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
