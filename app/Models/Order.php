<?php

namespace App\Models;

use App\Models\Access\User\User;
use App\Services\Orders\OrderProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {


    use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = ['id'];

    protected $appends = ['can_return'];

    public function children()
    {
        return $this->hasMany('App\Models\ChildOrder', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\OrderProductView', 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id', 'id');
    }

    public function billings()
    {
        return $this->hasMany('App\Models\OrderBilling', 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(PingxxPayment::class, 'order_id', 'id');
    }

    public function refund()
    {
        return $this->hasMany(OrderRefund::class);
    }

    public function getCanReturnAttribute()
    {
        return OrderProtocol::validStatus($this->attributes['status'], OrderProtocol::STATUS_OF_RETURN_APPLY, false) && $this->attributes['pay_at'] < \Carbon\Carbon::now()->subDay(7);
    }

}
