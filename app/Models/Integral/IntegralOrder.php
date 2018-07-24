<?php

namespace App\Models\Integral;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;

class IntegralOrder extends Model
{
    protected $table='integral_orders';

    protected $guarded = ['id'];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
