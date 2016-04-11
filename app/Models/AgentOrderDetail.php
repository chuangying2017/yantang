<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentOrderDetail extends Model {

    protected $table = 'agent_order_detail';

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(AgentOrder::class, 'agent_order_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function address()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'agent_order_id');
    }



}
