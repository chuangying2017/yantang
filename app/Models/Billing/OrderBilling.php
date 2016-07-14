<?php

namespace App\Models\Billing;

use App\Models\Pay\PingxxPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderBilling extends Model {

    use SoftDeletes;
    protected $table = 'order_billing';

    protected $guarded = ['id'];

    public function payment()
    {
        return $this->morphMany(PingxxPayment::class, 'billing');
    }

    public function scopeOrder($query, $order_id)
    {
        return $query->where('order_id', $order_id);
    }

}
