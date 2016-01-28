<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $guarded = ['id'];

    public function agent()
    {
        return $this->morphTo();
    }
}
