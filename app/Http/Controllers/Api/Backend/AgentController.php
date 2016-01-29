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




}
