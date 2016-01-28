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
        $agent_id = AgentService::AGENT_ID;
        $agent = AgentService::getAgentEarn($agent_id);

        return $this->response->array(['data' => $agent]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $start_at = $request->input('start_at') ?: null;
        $end_at = $request->input('end_at') ?: null;
        $agent_id = AgentService::AGENT_ID;
        $agent = AgentService::getAgent($agent_id, $start_at, $end_at);

        return $this->response->array(['data' => self::transformAgent($agent)]);
    }

    public function subDetail(Request $request, $agent_id)
    {
        $start_at = $request->input('start_at') ?: null;
        $end_at = $request->input('end_at') ?: null;
        $agent = AgentService::getAgent($agent_id, $start_at, $end_at);

        return $this->response->array(['data' => self::transformAgent($agent)]);
    }

    protected static function transformAgent($agent)
    {
        if (isset($agent->orders)) {
            foreach ($agent->orders as $key => $order) {
                $agent->orders[ $key ]['amount'] = display_price($order['amount']);
            }
        }

        return $agent;
    }



}
