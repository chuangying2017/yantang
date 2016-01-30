<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\AgentOrder;
use App\Models\AgentOrderDetail;
use App\Models\AgentRate;
use Cache;
use Carbon\Carbon;

class AgentRepository {

    const CACHE_KEY_OF_RATE = 'dflr_agent_rates';

    public static function rate()
    {
        return Cache::rememberForever(self::CACHE_KEY_OF_RATE, function () {
            return AgentRate::lists('rate', 'level');
        });
    }

    public static function updateRate($id, $rate)
    {
        $agent = AgentRate::findOrFail($id);
        $agent['rate'] = store_percentage($rate);
        $agent->save();

        Cache::forget(self::CACHE_KEY_OF_RATE);

        return $agent;
    }

    public static function storeRate($level, $rate, $name)
    {
        $rate_data = AgentRate::updateOrCreate(['level' => $level], ['level' => $level, 'rate' => $rate, 'name' => $name]);

        Cache::forget(self::CACHE_KEY_OF_RATE);

        return $rate_data;
    }

    public static function listsRate()
    {
        return AgentRate::get();
    }

    public static function showRate($id)
    {
        return AgentRate::firstOrFail($id);
    }

    public static function byId($agent_id)
    {
        if ($agent_id instanceof Agent) {
            return $agent_id;
        }

        return Agent::findOrFail($agent_id);
    }

    public static function byNo($agent_no)
    {
        if ($agent_no instanceof Agent) {
            return $agent_no;
        }

        return Agent::where('no', $agent_no)->firstOrFail();
    }

    public static function byUser($user_id, $all = false)
    {
        if ($all) {
            return Agent::where('user_id', $user_id)->orderBy('level')->get();
        }

        return Agent::where('user_id', $user_id)->orderBy('level')->first();
    }

    public static function getAgentIdByUser($user_id, $all = false)
    {
        if ($all) {
            return Agent::where('user_id', $user_id)->orderBy('level')->lists('id')->toArray();
        }

        return Agent::where('user_id', $user_id)->orderBy('level')->pluck('id');
    }

    public static function setRealAgent($agent_id, $user_id)
    {
        $agent = self::byId($agent_id);
        $agent->user_id = $user_id;
        $agent->mark = AgentProtocol::MARK_REAL_AGENT;
        $agent->save();

        return $agent;
    }


    public static function createStoreAgent($parent_agent, $apply_info)
    {
        $agent = Agent::create([
            'user_id' => $apply_info['user_id'],
            'mark'    => 1,
            'level'   => AgentProtocol::AGENT_LEVEL_OF_STORE,
            'name'    => $apply_info['name'],
            'no'      => self::genStoreNo($parent_agent)
        ]);

        $agent->makeChildOf($parent_agent);

        return $agent;
    }

    protected static function genStoreNo($parent_agent)
    {
        return $parent_agent['no'] . sprintf("%04d", $parent_agent->leaves()->count() + 1);
    }

    public static function setTempAgent($agent_ids, $user_id)
    {
        return Agent::whereIn('id', $agent_ids)->update(['user_id' => $user_id, 'mark' => AgentProtocol::MARK_TEMP_AGENT]);
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

    public static function listsAgentOrderDetail($agent_id, $start_at = null, $end_at = null, $status = AgentProtocol::AGENT_ORDER_STATUS_OF_OK, $paginate = 20)
    {
        $agent_ids = to_array($agent_id);

        $start_at = is_null($start_at) ?: Carbon::createFromDate(2016, 1, 1);
        $end_at = is_null($end_at) ?: Carbon::now();

        return AgentOrderDetail::whereIn('agent_id', $agent_ids)->whereBetween('created_at', [$start_at, $end_at])->where('status', $status)->paginate($paginate);
    }

    public static function getAgentOrders($agent_id, $start_at, $end_at)
    {
        return self::listsAgentOrderDetail(self::getStoreIds($agent_id), $start_at, $end_at);
    }

    public static function getStoreIds($agent_id)
    {
        $agent = self::byId($agent_id);

        if (AgentService::isSystemAgent($agent)) {
            return Agent::where('level', AgentProtocol::AGENT_LEVEL_OF_STORE)->lists('id');
        }

        return $agent->leaves()->where('level', AgentProtocol::AGENT_LEVEL_OF_STORE)->lists('id');
    }

    public static function getAgentsRoot()
    {
        return Agent::roots()->where('id', '!=', AgentProtocol::SYSTEM_AGENT_ID)->get(['id', 'name', 'no', 'level', 'mark']);
    }

    public static function getAgentUpTree($agent_id, $depth = null)
    {
        $agent = self::byId($agent_id);

        if ($agent->isRoot()) {
            return $agent;
        }

        if ( ! is_null($depth)) {
            $agents = $agent->ancestorsAndSelf()->limitDepth($depth)->get(['id', 'name', 'no', 'level', 'mark', 'pid']);
        } else {
            $agents = $agent->getAncestorsAndSelf(['id', 'name', 'no', 'level', 'mark', 'pid'])->toHierarchy();
            $agents = current($agents->toArray());
        }

        return $agents;
    }

    public static function getAgentTree($agent_id, $depth = 1)
    {
        $agent = self::byId($agent_id);


//        return Cache::rememberForever('agent_tree_' . $agent_id, function ($agent) {
//            return $agent->descendantsAndSelf()->get(['id', 'name', 'no', 'level', 'mark']);
//
//        });

        if ( ! is_null($depth)) {
            $agents = $agent->descendantsAndSelf()->limitDepth($depth)->get(['id', 'name', 'no', 'level', 'mark', 'pid']);
        } else {
            $agents = $agent->getDescendantsAndSelf(['id', 'name', 'no', 'level', 'mark', 'pid'])->toHierarchy();
            $agents = $agents[ $agent_id ];
        }

        return $agents;
    }


}
