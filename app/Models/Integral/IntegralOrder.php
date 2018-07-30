<?php

namespace App\Models\Integral;

use App\Models\Access\User\User;
use App\Models\Access\User\UserProvider;
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

    public function user_provider()
    {
        return $this->belongsTo(UserProvider::class,'user_id','user_id');
    }
}
