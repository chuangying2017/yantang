<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\AgentOrder;
use App\Models\AgentOrderDetail;
use App\Models\AgentRate;
use Cache;

class AgentRepository {

    public static function rate()
    {
        return Cache::rememberForever('agent_rates', function () {
            return AgentRate::lists('rate', 'level');
        });
    }

    public static function listsRate()
    {
        return AgentRate::get();
    }

    public static function byId($agent_id)
    {
        if ($agent_id instanceof Agent) {
            return $agent_id;
        }

        return Agent::findOrFail($agent_id);
    }


    public static function storeAgentOrders($agent, $order)
    {
        return AgentOrder::firstOrCreate([
            'agent_id'   => $agent['id'],
            'agent_type' => $agent instanceof Agent ? AgentProtocol::AGENT_TYPE_OF_AGENT : AgentProtocol::AGENT_TYPE_OF_CLIENT,
            'order_no'   => $order['order_no'],
            'amount'     => $order['pay_amount']
        ]);
    }

    public static function storeAgentOrderDetail($orders)
    {
        if (count($orders)) {
            foreach ($orders as $order) {
                AgentOrderDetail::updateOrCreate(
                    array_only($order, ['agent_order_id', 'agent_id']),
                    $order
                );
            }
        }

        return 1;
    }

    public static function getAgentsRoot()
    {
        return Agent::roots()->get(['id', 'name', 'no', 'level', 'mark']);
    }

    public static function getAgentTree($agent_id)
    {
        $agent = self::byId($agent_id);


//        return Cache::rememberForever('agent_tree_' . $agent_id, function ($agent) {
//            return $agent->descendantsAndSelf()->get(['id', 'name', 'no', 'level', 'mark']);
//        });
        $agents = $agent->getDescendantsAndSelf(['id', 'name', 'no', 'level', 'mark', 'pid'])->toHierarchy();

        return $agents[$agent_id];
    }


}
