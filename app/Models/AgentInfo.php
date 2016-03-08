<?php

namespace App\Models;

use App\Models\Access\User\User;
use App\Services\Agent\AgentProtocol;
use Illuminate\Database\Eloquent\Model;

class AgentInfo extends Model {

    protected $table = 'agent_apply_info';

    protected $guarded = ['id'];

    protected $appends = ['agent_role_name'];

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

    public function getAgentRoleNameAttribute()
    {
        return AgentProtocol::name($this->attributes['agent_role']);
    }


}
