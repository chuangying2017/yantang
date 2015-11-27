<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
