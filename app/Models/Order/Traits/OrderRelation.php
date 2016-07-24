<?php namespace App\Models\Order\Traits;

use App\Models\Access\User\User;
use App\Models\Billing\OrderBilling;
use App\Models\Comment;
use App\Models\Order\Order;
use App\Models\Order\OrderAddress;
use App\Models\Order\OrderDeliver;
use App\Models\Order\OrderMemo;
use App\Models\Order\OrderSku;
use App\Models\Order\OrderSpecialCampaign;

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

    public function memo()
    {
        return $this->hasOne(OrderMemo::class, 'order_id', 'id');
    }

    public function deliver()
    {
        return $this->hasOne(OrderDeliver::class, 'order_id', 'id');
    }

    public function special()
    {
        return $this->hasOne(OrderSpecialCampaign::class, 'order_id', 'id');
    }

    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }

    public function refund()
    {
        return $this->belongsToMany(Order::class, 'return_order', 'order_id', 'return_order_id');
    }

    public function refer()
    {
        return $this->belongsToMany(Order::class, 'return_order', 'return_order_id', 'order_id');
    }

}
