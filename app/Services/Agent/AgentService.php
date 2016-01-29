<?php namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\AgentOrder;
use App\Services\Agent\Event\NewAgentOrder;
use App\Services\Client\ClientService;
use App\Services\Orders\OrderRepository;
use Carbon\Carbon;

class AgentService {

    const STAFF_ID = 5;
    const AGENT_ID = 1;


    public static function orderDeal($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);
        $agent = self::getAgentByUser($order['user_id']);

        if ($agent) {
            $agent_order = AgentRepository::storeAgentOrders($agent, $order);

            event(new NewAgentOrder($agent_order));

            return $agent_order;
        }

        return false;
    }

    public static function awardAgent($agent_order)
    {
        $award_agents = self::getParentAgents($agent_order['agent_id']);

        $award_orders = [];
        foreach ($award_agents as $key => $award_agent) {
            $rate = self::getEarnRate($award_agent['level']);
            $award_orders[ $key ] = [
                'agent_order_id' => $agent_order['id'],
                'agent_id'       => $award_agent['id'],
                'agent_level'    => $award_agent['level'],
                'order_no'       => $agent_order['order_no'],
                'status'         => AgentProtocol::AGENT_ORDER_STATUS_OF_OK,
                'amount'         => $agent_order['amount'],
                'rate'           => $rate,
                'award_amount'   => bcdiv((bcmul($agent_order['amount'], $rate)), AgentProtocol::AGENT_RATE_BASE, 0),
            ];
        }

        if (count($award_orders)) {
            AgentRepository::storeAgentOrderDetail($award_orders);
        }
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
        $data = AgentRepository::rate();

        return array_get($data, $agent_level, 0);
    }

    /**
     * 查询用户是否有推荐人
     * @param $user_id
     */
    public static function getAgentByUser($user_id)
    {
        $client = ClientService::show($user_id);

        if ($promotion_id = $client['promotion_id']) {
            return PromotionRepository::getAgentByPromotion($promotion_id);
        }

        return false;
    }


    public static function getAgentOrders($agent_id, $start_at = null, $end_at = null)
    {
        $staff_ids = self::getLeavesId($agent_id);
        $start_at = is_null($start_at) ? Carbon::createFromDate(2015, 10, 1) : $start_at;
        $end_at = is_null($end_at) ? Carbon::tomorrow() : $end_at;

        return AgentOrder::whereIn('agent_id', $staff_ids)->whereBetween('created_at', [$start_at, $end_at])->get();
    }

    public static function getPromotionId($code)
    {
        if ( ! is_null($code) && $code) {
            $promotion = PromotionRepository::getByCode($code);
            if ($promotion) {
                return $promotion['id'];
            }
        }

        return 0;
    }

    public static function getClientPromotion($user_id)
    {
        return PromotionRepository::getByAgent($user_id);
    }

    public static function getAgentPromotion($agent_id)
    {
        return PromotionRepository::getByAgent($agent_id);
    }

    public static function getParentAgents($agent_id)
    {
        $agent = AgentRepository::byId($agent_id);

        return $agent->ancestorsAndSelf()->get();
    }


    /**********************************************************************************************************
     *
     *
     * Agent Apply
     *
     *
     **********************************************************************************************************/

    public static function userApply($user_id)
    {
        return AgentApplyRepository::byUser($user_id);
    }

    public static function newApply($user_id, $data)
    {
        $apply = self::userApply($user_id);

        $parent_agent_id = $data['parent_agent_id'];
        $apply_agent_id = $data['apply_agent_id'];
        $parent_agent = AgentRepository::byId($parent_agent_id);

        if ( ! $apply_agent_id || is_null($apply_agent_id)) {
            $data['apply_agent_id'] = null;
            if ( ! $parent_agent['level'] !== AgentProtocol::AGENT_LEVEL_OF_REGION) {
                throw new \Exception('申请的门店需要选择正确的区域');
            }
            $data['agent_role'] = AgentProtocol::AGENT_LEVEL_OF_STORE;

        } else {
            $apply_agent = AgentRepository::byId($apply_agent_id);

            if ($parent_agent['level'] != AgentProtocol::upperLevel($apply_agent['level'])) {
                throw new \Exception('请选择正确的上级代理');
            }

            $data['agent_role'] = $apply_agent['level'];
        }

        //用户已经通过不需要再提交
        if ($apply) {
            if ($apply['status'] == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
                throw new \Exception('用户已经是代理商,无需再次提交');
            }
        }

        return AgentApplyRepository::storeApplyInfo($user_id, $data);
    }

    public static function agentCheckApplyList($agent_id, $status = AgentProtocol::APPLY_STATUS_OF_PENDING)
    {
        return AgentApplyRepository::byAgent($agent_id, $status);
    }

    public static function approveApply($apply_id, $handle_user_id)
    {
        #todo 检查是否有通过权限

        $apply = AgentApplyRepository::byId($apply_id);

        $apply_agent_id = $apply['apply_agent_id'];

        $handle_agent = self::getAgentByUser($handle_user_id);

        if ( ! $handle_agent) {
            throw new \Exception('通过操作不合法');
        }


        if ($apply['status'] == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
            return false;
        }


        //门店代理
        if ( ! $apply_agent_id || is_null($apply_agent_id)) {
            return self::approveStoreAgent($apply);
        }

        //普通代理
        $agent = AgentRepository::byId($apply_agent_id);

        if ($agent['mark']) {
            throw new \Exception('指定代理已存在');
        }

        $agent = self::approveNormalAgent($agent, $apply['user_id']);
        AgentApplyRepository::updateStatus($apply_id, AgentProtocol::APPLY_STATUS_OF_APPROVE);

        return $agent;
    }

    public static function approveNormalAgent($agent, $user_id)
    {
        $agent = AgentRepository::setRealAgent($agent, $user_id);
        if ($agent['level'] !== AgentProtocol::AGENT_LEVEL_OF_REGION) {

            $children = $agent->getDescendants();
            $child_agent_ids = [];
            if (count($children)) {
                foreach ($children as $child) {
                    if ( ! $child['mark']) {
                        array_push($child_agent_ids, $child['id']);
                    }
                }
            }
            if (count($child_agent_ids)) {
                AgentRepository::setTempAgent($child_agent_ids, $user_id);
            }
        }

        return $agent;
    }

    public static function approveStoreAgent($apply_info)
    {
        $parent_id = $apply_info['parent_agent_id'];
        $parent_agent = AgentRepository::byId($parent_id);

        return AgentRepository::createStoreAgent($parent_agent, $apply_info);
    }

    public static function getAgentTree($agent_id = null, $depth = 1)
    {
        if (is_null($agent_id)) {
            return AgentRepository::getAgentsRoot();
        }

        return AgentRepository::getAgentTree($agent_id, $depth);
    }

}
