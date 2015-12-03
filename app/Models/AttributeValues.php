<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValues extends Model
{
    use SoftDeletes;

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }

    public function skus()
    {
        return $this->belongsTo('App\Models\ProductSku');
    }

}
