<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class IntegralCategory extends Model
{

    use SoftDeletes;

    protected $table = 'integral_category';

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function integral_product()
    {
        return $this->belongsToMany(Product::class,'integral_product_cate','category_id','product_id');
    }
}
