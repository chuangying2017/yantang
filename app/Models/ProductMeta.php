<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMeta extends Model
{
    use SoftDeletes;

    protected $table = 'product_meta';

    protected $guarded = ['id'];
}
