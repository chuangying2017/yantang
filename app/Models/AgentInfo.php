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

    public function setContractImageAttribute($value)
    {
        if ( ! is_null($value))
            $this->attributes['contract_image'] = serialize($value);
        else
            $this->attributes['contract_image'] = $value;
    }

    public function getContractImageAttribute()
    {
        if ($this->attributes['contract_image']) {
            $array = @unserialize($this->attributes['contract_image']);
            if ($array === false && $this->attributes['contract_image'] !== 'b:0;') {
                return to_array($this->attributes['contract_image']);
            }

            return $array;
        }

        return to_array($this->attributes['contract_image']);
    }

}
