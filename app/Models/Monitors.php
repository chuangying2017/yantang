<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitors extends Model
{
    //
    protected $table = 'monitor';

    protected $guard = [];

    protected $fillable = ['action','openid','created_at','updated_at'];
}
