<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    protected $fillable = ['value', 'attribute_id'];

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }

    public function skus()
    {
        return $this->belongsTo('App\Models\ProductSku');
    }

}
