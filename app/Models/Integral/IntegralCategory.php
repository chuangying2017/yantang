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

    }
}
