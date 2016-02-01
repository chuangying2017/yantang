<?php namespace App\Services\Agent;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Models\Agent;
use App\Models\AgentOrder;
use App\Models\AgentOrderDetail;
use App\Services\Agent\Event\NewAgent;
use App\Services\Agent\Event\NewAgentOrder;
use App\Services\Client\ClientService;
use App\Services\Orders\OrderRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AgentService {


    public static function byId($agent_id)
    {
        return AgentRepository::byId($agent_id);
    }

    public static function getAgentByUser($user_id, $all = false)
    {
        $agent = AgentRepository::byUser($user_id, $all);

        $agent->load('info');

        if (($agent && $all) || count($agent)) {
            return $agent;
        }

        throw new \Exception('非代理商,请先申请');
    }


    public static function orderDeal($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);
        $agent = self::getPromotionAgentByUser($order['user_id']);

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

    protected static function getEarnRate($agent_level)
    {
        $data = AgentRepository::rate();

        return array_get($data, $agent_level, 0);
    }

    /**
     * 查询用户是否有推荐人
     * @param $user_id
     */
    public static function getPromotionAgentByUser($user_id)
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


    public static function needCheckAgentApply($agent_id, $level)
    {
        $agent = self::byId($agent_id);

        return AgentApplyRepository::lists($agent['id'], AgentProtocol::lowerLevel($agent['level']));
    }

    public static function needCheckAgentApplyByUser($user_id)
    {
        $agent_ids = AgentRepository::getAgentIdByUser($user_id, true);

        if (in_array(AgentProtocol::SYSTEM_AGENT_ID, $agent_ids)) {
            return AgentApplyRepository::lists();
        }

        return AgentApplyRepository::lists($agent_ids);
    }

    public static function getApplyById($apply_id, $user_id)
    {
        if ($apply = self::authAgentApply($apply_id, $user_id)) {
            return AgentApplyRepository::byId($apply_id, true);
        }

        throw new UnauthorizedException('权限不足,无法通过代理商申请');
    }

    public static function authAgentApply($apply_id, $handle_user_id)
    {
        $apply = AgentApplyRepository::byId($apply_id);

        $handle_agent = self::getAgentByUser($handle_user_id);

        //普通代理
        $parent_agent = AgentRepository::byId($apply['parent_agent_id']);

        //检查是否有通过权限
        if ($handle_agent->isSelfOrAncestorOf($parent_agent)) {
            return $apply;
        }

        return false;
    }

    public static function userApply($user_id, $detail = false)
    {
        return AgentApplyRepository::byUser($user_id, $detail);
    }


    public static function newApply($user_id, $data)
    {
        try {
            $apply = self::userApply($user_id);

            //用户已经通过不需要再提交
            if ($apply) {
                if ($apply['status'] == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
                    throw new \Exception('用户已经是代理商,无需再次提交');
                }
            }
        } catch (ModelNotFoundException $e) {

        } catch (\Exception $e) {
            throw $e;
        }

        $parent_agent_id = array_get($data, 'parent_agent_id', null);
        if (is_null($parent_agent_id)) {
            $parent_agent = AgentRepository::byNo(array_get($data, 'parent_agent_no'));
            $parent_agent_id = $parent_agent['id'];
        } else {
            $parent_agent = AgentRepository::byId($parent_agent_id);
        }

        $apply_agent_id = $data['apply_agent_id'];


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

        return AgentApplyRepository::storeApplyInfo($user_id, $data);
    }

    public static function agentCheckApplyList($agent_id, $status = AgentProtocol::APPLY_STATUS_OF_PENDING)
    {
        return AgentApplyRepository::byAgent($agent_id, $status);
    }

    public static function approveApply($apply_id, $handle_user_id)
    {

        $apply = AgentApplyRepository::byId($apply_id);

        $apply_agent_id = $apply['apply_agent_id'];

        $handle_agent = self::getAgentByUser($handle_user_id);

        if ( ! $handle_agent) {
            throw new \Exception('通过操作不合法');
        }

        if ($apply['status'] == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
            throw new \Exception('已通过,无需重复操作');
        }


        //门店代理
        if ( ! $apply_agent_id || is_null($apply_agent_id)) {
            $agent = self::approveStoreAgent($apply);
        } else {
            //普通代理
            $agent = AgentRepository::byId($apply_agent_id);


            //检查是否有通过权限
            if ( ! self::isSystemAgent($handle_agent) && ! $handle_agent->isAncestorOf($agent)) {
                throw new \Exception('权限不足,无法通过代理商申请');
            }

            if ($agent['mark']) {
                throw new \Exception('指定代理已存在');
            }

            $agent = self::approveNormalAgent($agent, $apply['user_id']);
            AgentApplyRepository::updateStatus($apply_id, AgentProtocol::APPLY_STATUS_OF_APPROVE);
            self::attachAgentRoleToUser($apply['user_id']);

        }

        event(new NewAgent($agent));

        return $agent;
    }

    public static function getPromotionCode($agent_id)
    {
        $agent = self::byId($agent_id);

        if (self::isStore($agent)) {
            return PromotionRepository::getByAgent($agent_id);
        }

        return false;
    }

    public static function isStore($agent)
    {
        return $agent['level'] == AgentProtocol::AGENT_LEVEL_OF_STORE;
    }

    public static function isSystemAgent($agent_id)
    {
        $agent = AgentRepository::byId($agent_id);

        return $agent['id'] == AgentProtocol::SYSTEM_AGENT_ID;
    }

    public static function rejectApply($apply_id, $user_id, $memo = '')
    {
        $apply = self::authAgentApply($apply_id, $user_id);

        return AgentApplyRepository::updateStatus($apply_id, AgentProtocol::APPLY_STATUS_OF_REJECT, $memo);
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

    public static function attachAgentRoleToUser($user_id)
    {
        $user = User::findOrFail($user_id);
        if ( ! $user->hasRole(AgentProtocol::AGENT_ROLE_NAME)) {
            $user->attachRole(Role::whereName(AgentProtocol::AGENT_ROLE_NAME)->first());
        }
    }

    public static function byNo($agent_no)
    {
        return AgentRepository::byNo($agent_no);
    }

    public static function getOrders($agent_id, $start_at = null, $end_at = null)
    {
        $orders = AgentRepository::getAgentOrders($agent_id, $start_at, $end_at);

        return $orders;
    }

    public static function getEarnData($agent_id)
    {
        $month_first_day = Carbon::today()->startOfMonth();
        $week_first_day = Carbon::today()->startOfWeek();
        $today = Carbon::today();
        $now = Carbon::now();
        $orders = self::getOrders($agent_id, $month_first_day, $now);

        $data = [
            "today_amount" => 0,
            "week_amount"  => 0,
            "month_amount" => 0
        ];

        if ( ! count($orders)) {
            return $data;
        }

        foreach ($orders as $order) {

            if ($order['created_at'] >= $today) {
                $data['today_amount'] += $order['award_amount'];
            }
            if ($order['created_at'] >= $week_first_day) {
                $data['week_amount'] += $order['award_amount'];
            }
            if ($order['created_at'] >= $month_first_day) {
                $data['month_amount'] += $order['award_amount'];
            }
        }

        return $data;
    }

}
