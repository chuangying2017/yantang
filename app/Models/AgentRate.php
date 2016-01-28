<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentRate extends Model
{
    use SoftDeletes;

    protected $table = 'agent_rate';

    protected $guarded = ['id'];

}
