<?php

namespace App\Services\Agent\Listeners;

use App\Services\Agent\AgentProtocol;
use App\Services\Agent\AgentService;
use App\Services\Agent\Event\NewAgentOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AwardAgent {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewAgentOrder $event
     * @return void
     */
    public function handle(NewAgentOrder $event)
    {
        $agent_order = $event->agent_order;

        if ($agent_order['agent_type'] == AgentProtocol::AGENT_TYPE_OF_AGENT) {
            AgentService::awardAgent($agent_order);
        }

    }
}
