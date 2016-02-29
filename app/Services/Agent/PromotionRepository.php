<?php namespace App\Services\Agent;

use App\Models\AgentOrder;
use App\Models\Promotion;
use App\Services\Client\ClientService;

class PromotionRepository {

    public static function _create($agent_id, $agent_type)
    {
        $code = str_random(5);

        while (Promotion::where('code', $code)->count()) {
            $code = str_random(5);
        }

        return Promotion::create([
            'agent_id'   => $agent_id,
            'agent_type' => $agent_type,
            'code'       => $code,
            'active'     => AgentProtocol::AGENT_ORDER_STATUS_OF_OK
        ]);
    }

    protected static function _query($agent_id, $agent_type)
    {
        return Promotion::where('agent_id', $agent_id)->where('agent_type', $agent_type)->first();
    }

    public static function firstOrCreate($agent_id, $agent_type)
    {
        return self::_query($agent_id, $agent_type) ?: self::_create($agent_id, $agent_type);
    }

    public static function getByClient($user_id)
    {
        return self::firstOrCreate($user_id, AgentProtocol::AGENT_TYPE_OF_CLIENT);
    }

    public static function getByAgent($agent_id)
    {
        return self::firstOrCreate($agent_id, AgentProtocol::AGENT_TYPE_OF_AGENT);
    }

    public static function getByCode($code)
    {
        return Promotion::where('code', $code)->first();
    }

    public static function fetch($promotion_id)
    {
        if ($promotion_id instanceof Promotion) {
            return $promotion_id;
        }

        return Promotion::find($promotion_id);
    }

    public static function getAgentByPromotion($promotion_id)
    {
        $promotion = self::fetch($promotion_id);
        if ($promotion) {
            return AgentRepository::byId($promotion['agent_id']);
        }

        return false;
    }


}
