<?php

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;

class AgentInfo extends Model {

    protected $table = 'agent_apply_info';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parentAgent()
    {
        return $this->hasOne(Agent::class, 'id', 'parent_agent_id');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'id', 'apply_agent_id');
    }


}
