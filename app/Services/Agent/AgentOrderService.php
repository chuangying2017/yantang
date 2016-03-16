<?php namespace App\Services\Agent;

use Carbon\Carbon;

class AgentOrderService {

    public static function getOrders($agent_id, $user_id = null, $start_at = null, $end_at = null, $status = null, $paginate = 20)
    {
        $orders = AgentRepository::getAgentOrders($agent_id, $user_id, $start_at, $end_at, $status, $paginate);

        return $orders;
    }

    public static function getOrdersCount($agent_id, $start_at = null, $end_at = null, $status = AgentProtocol::AGENT_ORDER_STATUS_OF_OK)
    {
        return \Cache::remember('east_beauty_agent_order_count' . $agent_id, 5, function () use ($agent_id, $start_at, $end_at, $status) {
            return AgentRepository::getAgentOrderDetailCount($agent_id, $start_at, $end_at, $status);
        });
    }

    public static function getEarnData($agent_id, $user_id = null)
    {
        $month_first_day = Carbon::today()->startOfMonth()->addDay(AgentProtocol::DELAY_DAYS);
//        $week_first_day = Carbon::today()->startOfWeek();
        $today = Carbon::today()->addDay(AgentProtocol::DELAY_DAYS);
        $now = Carbon::now()->addDay(AgentProtocol::DELAY_DAYS);
        $paginate = null;
        $orders = self::getOrders($agent_id, $user_id, $month_first_day, $now, $paginate);

        $data = [
            "today_amount"      => 0,
            "user_count"        => AgentService::agentUsersCount($agent_id),
            "month_amount"      => 0,
            'total_order_count' => self::getOrdersCount($agent_id)
        ];

        if ( ! count($orders)) {
            return $data;
        }

        foreach ($orders as $order) {

            if ($order['created_at'] >= $today) {
                $data['today_amount'] += $order['award_amount'];
            }
//            if ($order['created_at'] >= $week_first_day) {
//                $data['week_amount'] += $order['award_amount'];
//            }
            if ($order['created_at'] >= $month_first_day) {
                $data['month_amount'] += $order['award_amount'];
            }
        }

        return $data;
    }

    public static function refund($order_id, $amount)
    {
        return AgentRepository::increaseOrderRefund($order_id, $amount);
    }


}
