<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    protected $table = 'tags';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }
}
