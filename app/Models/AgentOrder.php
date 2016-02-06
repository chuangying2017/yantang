<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentOrder extends Model
{
    protected $table = 'agent_orders';

    protected $guarded = ['id'];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

}
