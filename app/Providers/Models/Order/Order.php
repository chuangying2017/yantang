<?php

namespace App\Models\Order;


use App\Models\Order\Traits\OrderRelation;
use App\Services\Order\OrderProtocol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes, OrderRelation;

    protected $table = 'orders';

    protected $guarded = ['id'];

    public function scopePaid($query)
    {
        $query->where('pay_status', OrderProtocol::PAID_STATUS_OF_PAID)
            ->where('refund_status', OrderProtocol::REFUND_STATUS_OF_DEFAULT);
    }

}
