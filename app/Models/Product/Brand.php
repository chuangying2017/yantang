<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model {

    use SoftDeletes;

    protected $table = 'brands';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
