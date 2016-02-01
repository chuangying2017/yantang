<?php

namespace App\Models;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Supports\PingxxProtocol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PingxxPayment extends Model {

    protected $table = 'pingxx_payments';

    protected $guarded = ['id'];

    public function scopePaid($query, $order_id)
    {
        return $query->where('order_id', $order_id)->where('status', OrderProtocol::STATUS_OF_PAID)->where('livemode', PingxxProtocol::LIVE_MODE);
    }

}
