<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class IntegralCategory extends Model
{
    //
    protected $table = 'integral_category';

    public $timestamps = false;

    protected $guarded = ['id'];
}
