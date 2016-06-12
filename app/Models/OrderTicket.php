<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model {

    protected $table = 'order_tickets';

    protected $guarded = ['id'];

    public function exchange()
    {
        return $this->hasOne(Store::class, 'store_id', 'id');
    }
}
