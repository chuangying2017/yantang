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
        return $this->belongsTo(User::class);
    }

    public function integral_order_sku()
    {
       return $this->hasOne(IntegralOrderSku::class,'order_id','id');
    }

    public function integral_order_address()
    {
        return $this->hasOne(IntegralOrderAddress::class,'order_id','id');
    }
}
