<?php

namespace App\Services\Agent\Listeners;

use App\Services\Agent\AgentProtocol;
use App\Services\Agent\Event\NewAgentOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AwardClientAgent {

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
        $order = $event->agent_order;

        if ($order['agent_type'] == AgentProtocol::AGENT_TYPE_OF_CLIENT) {
            #发送用户奖品

        }

    }
}
