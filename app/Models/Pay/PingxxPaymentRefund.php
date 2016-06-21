<?php

namespace App\Models\Pay;

use Illuminate\Database\Eloquent\Model;

class PingxxPaymentRefund extends Model
{
    protected $table = 'pingxx_refund';

    protected $guarded = ['id'];
}
