<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PingxxRefund extends Model {

    protected $table = 'pingxx_refund';

    protected $guarded = ['id'];

    public function payment()
    {
        return $this->belongsTo(PingxxPayment::class, 'pingxx_payment_id', 'id');
    }


}
