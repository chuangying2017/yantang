<?php namespace App\Services\Agent;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Models\Agent;
use App\Models\AgentOrder;
use App\Models\AgentOrderDetail;
use App\Services\Agent\Event\NewAgent;
use App\Services\Agent\Event\NewAgentOrder;
use App\Services\Client\ClientRepository;
use App\Services\Client\ClientService;
use App\Services\Orders\OrderRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AgentService {


    public static function checkAccess($current_agent, $access_agent)
    {
        $access_agent = self::byId($access_agent);
        if ( ! self::canAccess($current_agent, $access_agent)) {
            throw new \Exception('权限不足,无法查看', 403);
        }

        return $access_agent;
    }

    //permission
    public static function canAccess($current_agent_id, $access_agent_id)
    {
        $current_agent = self::byId($current_agent_id);
        $access_agent = self::byId($access_agent_id);

        return $current_agent->isSelfOrAncestorOf($access_agent) || self::isSystemAgent($current_agent);
    }

    public static function byId($agent_id)
    {
        return AgentRepository::byId($agent_id);
    }

    public static function getAgentByUser($user_id, $all = false)
    {
        $agent = AgentRepository::byUser($user_id, $all);

        $agent->load('info');

        $agent->promotion_code = self::getAgentPromotionCode($agent['id']);

        if (($agent && $all) || count($agent)) {
            return $agent;
        }


        throw new \Exception('非代理商,请先申请');
    }

    public static function getAgentById($agent_id)
    {
        $agent = self::byId($agent_id);

        $agent->load('info');

        $agent->promotion_code = self::getAgentPromotionCode($agent['id']);

        $agent->earn_data = self::getEarnData($agent_id, $agent['user_id']);

        return $agent;
    }


    public static function orderDeal($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);
        $agent = self::getPromotionAgentByUser($order['user_id']);

        if ($agent) {
            $agent_order = AgentRepository::storeAgentOrders($agent, $order);

            event(new NewAgentOrder($agent_order));

            info('new agent order deal, order_id:' . $order_id . ', agent_id: ' . $agent['id']);

            return $agent_order;
        }

        info('not a agent order order_id: ' . $order_id);

        return false;
    }

    public static function awardAgent($agent_order)
    {

        $award_agents = self::getParentAgents($agent_order['agent_id']);
        $award_agents[] = self::getSystemAgent();

        $award_orders = [];
        $award_agents = self::filterAwardAgents($award_agents);

        foreach ($award_agents as $key => $award_agent) {
            $rate = array_get($award_agent, 'rate', 0);
            $award_orders[ $key ] = [
                'agent_order_id' => $agent_order['id'],
                'agent_id'       => $award_agent['id'],
                'user_id'        => $award_agent['user_id'],
                'agent_level'    => $award_agent['level'],
                'order_no'       => $agent_order['order_no'],
                'status'         => AgentProtocol::AGENT_ORDER_STATUS_OF_OK,
                'amount'         => $agent_order['amount'],
                'rate'           => $rate,
                'award_amount'   => bcdiv((bcmul($agent_order['amount'], $rate)), AgentProtocol::AGENT_RATE_BASE, 0),
            ];
        }

        if (count($award_orders)) {
            AgentRepository::storeAgentOrderDetail($award_orders, $agent_order['id']);
        }
    }

    protected static function filterAwardAgents($origin_award_agents)
    {
        $rates = [];
        $award_agents = [];
        $rate_id = 0;
        foreach ($origin_award_agents as $award_agent) {
            if ($award_agent['mark']) {
                $award_agents[] = $award_agent;
                $rates[ ++$rate_id ] = $award_agent['level'];
            }
        }


        sort($rates);

        $rate_data = [];
        foreach ($rates as $key => $level) {
            $rate_data[ $level ] = self::getEarnRate($level, array_get($rates, $key + 1, AgentProtocol::AGENT_LEVEL_OF_STORE + 1) - 1);
        }

        foreach ($award_agents as $key => $award_agent) {
            $award_agents[ $key ]['rate'] = array_get($rate_data, $award_agent['level'], self::getEarnRate($award_agent['level']));
        }

        return $award_agents;
    }

    public static function getSystemAgent()
    {
        return AgentService::byId(AgentProtocol::SYSTEM_AGENT_ID);
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


    protected static function getEarnRate($agent_level, $last = false)
    {
        $data = AgentRepository::rate();
        $result = 0;

        if ($last && $last > $agent_level) {
            for ($i = $agent_level; $i <= $last; $i++) {
                $result = $result + array_get($data, $i, 0);
            }

            return $result;
        }

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
        return PromotionRepository::getByClient($user_id);
    }

    public static function getAgentPromotion($agent_id)
    {
        return PromotionRepository::getByAgent($agent_id);
    }

    public static function getAgentPromotionCode($agent_id)
    {
        $promotion = self::getAgentPromotion($agent_id);

        return $promotion['code'];
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
        if ($handle_agent->isSelfOrAncestorOf($parent_agent) || self::isSystemAgent($handle_agent)) {
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
        if (is_null($agent_id) || $agent_id == AgentProtocol::SYSTEM_AGENT_ID) {
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

    public static function getOrders($agent_id, $user_id = null, $start_at = null, $end_at = null, $status = null, $paginate = 20)
    {
        $orders = AgentRepository::getAgentOrders($agent_id, $user_id, $start_at, $end_at, $status, $paginate);

        return $orders;
    }

    public static function getOrdersCount($agent_id, $start_at = null, $end_at = null, $status = null)
    {
        return \Cache::remember('east_beauty_agent_order_count' . $agent_id, 5, function () use ($agent_id, $start_at, $end_at, $status) {
            return AgentRepository::getAgentOrderDetailCount($agent_id, $start_at, $end_at, $status);
        });
    }

    public static function getEarnData($agent_id, $user_id = null)
    {
        $month_first_day = Carbon::today()->startOfMonth();
//        $week_first_day = Carbon::today()->startOfWeek();
        $today = Carbon::today();
        $now = Carbon::now();
        $paginate = null;
        $orders = self::getOrders($agent_id, $user_id, $month_first_day, $now, $paginate);

        $data = [
            "today_amount"      => 0,
//            "week_amount"  => 0,
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

    public static function updateApplyInfo($agent_info_id, $data)
    {
        $apply = AgentApplyRepository::byId($agent_info_id);

        if ($apply['user_id'] !== get_current_auth_user_id()) {
            throw new \Exception('没有权限更新', 403);
        }

        return AgentApplyRepository::update($apply, $data);
    }


    public static function agentUsers($agent_id)
    {
        $promotion = self::getAgentPromotion($agent_id);

        return ClientRepository::getByPromotionId($promotion['id']);
    }
}
