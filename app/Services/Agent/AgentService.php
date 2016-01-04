<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\AgentOrder;
use App\Services\Orders\OrderRepository;
use Carbon\Carbon;

class AgentService {

    const STAFF_ID = 5;
    const AGENT_ID = 1;


    public static function orderDeal($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);
        $agent_id = self::getAgentByUser($order['user_id']);

        $agent_order = AgentOrder::firstOrCreate([
            'agent_id' => $agent_id,
            'order_no' => $order['order_no'],
            'amount'   => $order['pay_amount']
        ]);

        return $agent_order;
    }

    public static function getLeavesId($agent_id, $string = false)
    {
        try {
            $agent = Agent::find($agent_id);

            if ($agent->isLeaf()) {
                return [$agent_id];
            }

            $categories = $agent->getLeaves(['id'])->toArray();

            $data = [];
            foreach ($categories as $agent) {
                $data[] = $agent['id'];
            }

            return count($data) ? ($string ? implode(',', $data) : $data) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAgent($agent_id, $start_at = null, $end_at = null)
    {
        $agent = Agent::find($agent_id);
        $orders = self::getAgentOrders($agent_id, $start_at, $end_at);

        $agent->children = $agent->descendants()->limitDepth(1)->get();
        $agent->earn_data = self::calAgentData($agent, $orders);
        foreach ($agent->children as $key => $child_agent) {
            $agent->children[ $key ]->earn_data = self::calAgentData($child_agent, $orders);
        }

        $agent->orders = $orders;

        return $agent;
    }

    public static function getAgentEarn($agent_id)
    {
        $agent = Agent::find($agent_id);
        $start_at = Carbon::today()->startOfMonth();
        $end_at = Carbon::now();
        $orders = self::getAgentOrders($agent_id, $start_at, $end_at);
        $agent->earn_data = self::splitOrderAmount($orders, $agent);

        return $agent;
    }

    protected static function splitOrderAmount($orders, $agent)
    {
        $data = [
            'today_amount' => 0,
            'week_amount'  => 0,
            'month_amount' => 0
        ];
        $today = Carbon::today();
        $monday = Carbon::today()->startOfWeek();
        foreach ($orders as $order) {
            if ($order['created_at'] > $today) {
                $data['today_amount'] = bcadd($data['today_amount'], $order['amount']);
                $data['week_amount'] = bcadd($data['week_amount'], $order['amount']);
                $data['month_amount'] = bcadd($data['month_amount'], $order['amount']);
            } else if ($order['created_at'] > $monday) {
                $data['week_amount'] = bcadd($data['week_amount'], $order['amount']);
                $data['month_amount'] = bcadd($data['month_amount'], $order['amount']);
            } else {
                $data['month_amount'] = bcadd($data['month_amount'], $order['amount']);
            }
        }

        $data['today_amount'] = display_price(bcdiv(bcmul($data['today_amount'], self::getEarnRate($agent['level'])), 100));
        $data['week_amount'] = display_price(bcdiv(bcmul($data['week_amount'], self::getEarnRate($agent['level'])), 100));
        $data['month_amount'] = display_price(bcdiv(bcmul($data['month_amount'], self::getEarnRate($agent['level'])), 100));

        return $data;
    }

    protected static function calAgentData($agent, $orders)
    {
        $agent_id = $agent['id'];
        $data = [
            'order_count'  => 0,
            'total_amount' => 0,
            'earn_amount'  => 0
        ];

        $staff_id = self::getLeavesId($agent_id);

        foreach ($orders as $order) {
            if (in_array($order['agent_id'], $staff_id)) {
                $data['order_count'] = $data['order_count'] + 1;
                $data['total_amount'] = bcadd($data['total_amount'], $order['amount']);
            }
        }

        $data['earn_amount'] = bcdiv(bcmul($data['total_amount'], self::getEarnRate($agent['level'])), 100);

        $data['earn_amount'] = display_price($data['earn_amount']);
        $data['total_amount'] = display_price($data['total_amount']);

        return $data;
    }

    protected static function getEarnRate($agent_level)
    {
        $data = [
            'sales' => 20,
            '1'     => 1,
            '2'     => 2,
            '3'     => 1,
            '4'     => 2
        ];

        return array_get($data, $agent_level, 1);
    }

    public static function getAgentByUser($user_id)
    {
        #todo order agent id
        return self::STAFF_ID;
    }

    public static function getAgentOrders($agent_id, $start_at = null, $end_at = null)
    {
        $staff_ids = self::getLeavesId($agent_id);
        $start_at = is_null($start_at) ? Carbon::createFromDate(2015, 10, 1) : $start_at;
        $end_at = is_null($end_at) ? Carbon::tomorrow() : $end_at;

        return AgentOrder::whereIn('agent_id', $staff_ids)->whereBetween('created_at', [$start_at, $end_at])->get();
    }

}
