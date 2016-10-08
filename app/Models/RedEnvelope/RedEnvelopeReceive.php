<?php

namespace App\Models\RedEnvelope;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\Ticket;
use Illuminate\Database\Eloquent\Model;

class RedEnvelopeReceive extends Model {

    protected $table = 'red_receives';

    protected $guarded = [];

    public function coupon()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }

}
