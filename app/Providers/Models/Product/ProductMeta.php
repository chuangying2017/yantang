<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMeta extends Model {

    protected $table = 'product_meta';

    public $timestamps = false;

    protected $primaryKey = 'product_id';


    protected $guarded = [];
}
