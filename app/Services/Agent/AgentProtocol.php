<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\Client;

class AgentProtocol {

    const AGENT_ROLE_NAME = 'Agent';
    const SYSTEM_AGENT_ID = 0;


    const APPLY_STATUS_OF_PENDING = 'pending';
    const APPLY_STATUS_OF_REJECT = 'reject';
    const APPLY_STATUS_OF_APPROVE = 'approve';

    const MARK_REAL_AGENT = 1;
    const MARK_TEMP_AGENT = 0;


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

    public static function upperLevel($level)
    {
        $upper_level = self::AGENT_LEVEL_OF_SYSTEM;
        switch ($level) {
            case self::AGENT_LEVEL_OF_PROVINCE:
                $upper_level = self::AGENT_LEVEL_OF_SYSTEM;
                break;
            case self::AGENT_LEVEL_OF_CITY:
                $upper_level = self::AGENT_LEVEL_OF_PROVINCE;
                break;
            case self::AGENT_LEVEL_OF_REGION:
                $upper_level = self::AGENT_LEVEL_OF_CITY;
                break;
            case self::AGENT_LEVEL_OF_STORE:
                $upper_level = self::AGENT_LEVEL_OF_REGION;
                break;
        }

        return $upper_level;
    }

    public static function name($level = null)
    {
        $data = [
            self::AGENT_LEVEL_OF_SYSTEM   => '东方丽人',
            self::AGENT_LEVEL_OF_PROVINCE => '省级',
            self::AGENT_LEVEL_OF_CITY     => '市级',
            self::AGENT_LEVEL_OF_REGION   => '区县',
            self::AGENT_LEVEL_OF_STORE    => '门店',
        ];

        return is_null($level) ? $data : array_get($data, $level, '');
    }

    public static function lowerLevel($level)
    {
        $lower_level = self::AGENT_LEVEL_OF_STORE;
        switch ($level) {
            case self::AGENT_LEVEL_OF_SYSTEM:
                $lower_level = self::AGENT_LEVEL_OF_PROVINCE;
                break;
            case self::AGENT_LEVEL_OF_PROVINCE:
                $lower_level = self::AGENT_LEVEL_OF_CITY;
                break;
            case self::AGENT_LEVEL_OF_CITY:
                $lower_level = self::AGENT_LEVEL_OF_REGION;
                break;
        }

        return $lower_level;
    }


}
