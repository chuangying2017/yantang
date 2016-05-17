<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
