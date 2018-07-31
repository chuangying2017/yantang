<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralCard extends Model
{
    use SoftDeletes;

    protected $table='integral_card';

    protected $guarded = ['id'];

}
