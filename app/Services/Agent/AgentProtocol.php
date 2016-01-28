<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\Client;

class AgentProtocol {

    const APPLY_STATUS_OF_PENDING = 'pending';
    const APPLY_STATUS_OF_REJECT = 'reject';
    const APPLY_STATUS_OF_APPROVE = 'approve';


    const AGENT_RATE_BASE = 10000;

    const AGENT_ORDER_STATUS_OF_OK = 0;
    const AGENT_ORDER_STATUS_OF_CANCEL = 1;
    const AGENT_ORDER_STATUS_OF_BANNED = 2;

    const AGENT_TYPE_OF_CLIENT = Client::class;
    const AGENT_TYPE_OF_AGENT = Agent::class;

    const AGENT_LEVEL_OF_SYSTEM = 1;
    const AGENT_LEVEL_OF_PROVINCE = 2;
    const AGENT_LEVEL_OF_CITY = 3;
    const AGENT_LEVEL_OF_REGION = 4;
    const AGENT_LEVEL_OF_STORE = 5;

}
