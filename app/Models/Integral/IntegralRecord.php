<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralRecord extends Model
{
    use SoftDeletes;

    protected $table = 'integral_record';

    protected $guarded = ['id'];
}
