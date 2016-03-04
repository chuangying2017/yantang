<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Services\Agent\AgentService;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AgentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();

        $agent = AgentService::getAgentByUser($user_id);

        return $this->response->array(['data' => $agent]);
    }

    public function show(Request $request, $agent_id)
    {
        $agents = AgentService::getAgentTree($agent_id);

        return $this->response->array($agents);
    }


    public function earnData(Request $request)
    {
        $user_id = $this->getCurrentAuthUserId();

        $agent = AgentService::getAgentByUser($user_id);


        $access_agent_id = $request->input('agent_id') ?: null;
        if ( ! is_null($access_agent_id)) {
            $agent = AgentService::checkAccess($agent, $access_agent_id);
        }


        $data = AgentService::getEarnData($agent['id'], $agent['user_id']);

        return $this->response->array(['data' => [
            'month_amount'      => display_price($data['month_amount']),
            'today_amount'      => display_price($data['today_amount']),
            'total_order_count' => ($data['total_order_count']),
            'user_count'        => ($data['user_count']),
        ]]);
    }

    public function info($agent_id)
    {
        $agent = AgentService::getAgentById($agent_id);

        return $this->response->array(['data' => $agent]);
    }

    public function orders(Request $request)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();

            $agent = AgentService::getAgentByUser($user_id);


            $access_agent_id = $request->input('agent_id') ?: null;
            if ( ! is_null($access_agent_id)) {
                $agent = AgentService::checkAccess($agent, $access_agent_id);
            }

            $start_at = $request->input('start_at') ?: null;
            $end_at = $request->input('end_at') ?: null;
            $status = $request->input('status') ?: null;

            $orders = AgentService::getOrders($agent['id'], $agent['user_id'], $start_at, $end_at, $status);

            return $this->response->array(['data' => self::transformer($orders, ['amount', 'award_amount'])]);
        } catch (Exception $e) {
            $this->response->errorForbidden($e->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function users(Request $request)
    {
        try {
            $user_id = get_current_auth_user_id();

            $agent = AgentService::getAgentByUser($user_id);

            $access_agent_id = $request->input('agent_id') ?: null;
            if ( ! is_null($access_agent_id)) {
                $agent = AgentService::checkAccess($agent, $access_agent_id);
            }

            $clients = AgentService::agentUsers($agent['id']);

            return $this->response->array(['data' => $clients]);

        } catch (Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }

    public static function transformer($data, $key_names = null, $price = true)
    {
        foreach ($data as $key => $value) {
            if (is_null($key_names)) {
                $data[ $key ] = display_price($value);
            } else {
                foreach ($key_names as $key_name)
                    $data[ $key ][ $key_name ] = display_price($value[ $key_name ]);
            }
        }

        return $data;
    }


}
