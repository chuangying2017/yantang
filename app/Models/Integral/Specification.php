<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table='specification';
    //
    protected $guarded=['id'];
}
