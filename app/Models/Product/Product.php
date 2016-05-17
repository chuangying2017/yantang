<?php

namespace App\Models\Product;

use App\Models\Product\Traits\ProductRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {

    use SoftDeletes, ProductRelation;

    protected $table = 'products';

    protected $guarded = ['id'];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
