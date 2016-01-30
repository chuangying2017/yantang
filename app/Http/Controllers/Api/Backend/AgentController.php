<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Services\Agent\AgentService;
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


    public function earnData()
    {
        $user_id = $this->getCurrentAuthUserId();

        $agent = AgentService::getAgentByUser($user_id);

        $data = AgentService::getEarnData($agent['id']);

        return $this->response->array(['data' => self::transformer($data)]);
    }

    public function orders(Request $request)
    {
        $user_id = $this->getCurrentAuthUserId();

        $agent = AgentService::getAgentByUser($user_id);

        $start_at = $request->input('start_at') ?: null;
        $end_at = $request->input('end_at') ?: null;
        $status = $request->input('status') ?: null;

        $orders = AgentService::getOrders($agent['id'], $start_at, $end_at, $status);

        return $this->response->array(['data' => self::transformer($orders, ['amount', 'award_amount'])]);
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
