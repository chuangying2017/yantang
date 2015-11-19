<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountLimit extends Model
{
    protected $table = 'discount_limit';
    protected $guarded = ['id'];

    public function resource()
    {
        return $this->morphTo();
    }
}
