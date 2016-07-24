<?php

namespace App\Models\Pay;

use Illuminate\Database\Eloquent\Model;

class PingxxPaymentRefund extends Model {

    protected $table = 'pingxx_refund';

    protected $guarded = ['id'];

    public function payment()
    {
        return $this->belongsTo(PingxxPayment::class, 'pingxx_payment_id', 'id');
    }

    public function billing()
    {
        return $this->morphTo('billing');
    }
}
