<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'category_attribute'));
    }
}
