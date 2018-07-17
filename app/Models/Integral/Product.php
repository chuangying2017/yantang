<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table='integral_product';
    //
    protected $guarded = ['id'];

    public function scopeStatus($query, $type)
    {
        return  $query->where('status',$type);
    }
}
