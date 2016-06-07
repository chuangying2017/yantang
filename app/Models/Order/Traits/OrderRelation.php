<?php namespace App\Models\Order\Traits;

use App\Models\Access\User\User;
use App\Models\Billing\OrderBilling;
use App\Models\Order\OrderAddress;
use App\Models\Order\OrderSku;

trait OrderRelation {

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skus()
    {
        return $this->hasMany(OrderSku::class, 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

    public function billings()
    {
        return $this->hasMany(OrderBilling::class, 'order_id', 'id');
    }



}
