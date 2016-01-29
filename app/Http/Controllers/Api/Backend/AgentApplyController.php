<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Services\Agent\AgentService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Toplan\PhpSms\Agent;

class AgentApplyController extends Controller {

    public function index()
    {
        try {
            $user_id = $this->getCurrentAuthUserId();

            $apply = AgentService::userApply($user_id);

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
            return $e->getTrace();
        }

    }

    //申请成为代理商
    public function store(ApplyAgentRequest $request)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $apply = AgentService::newApply($user_id, $request->get('data'));

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
//            return $e->getTrace();
            $this->response->errorInternal($e->getMessage());
        }
    }

    public function agents($agent_id = null)
    {
        $agents = AgentService::getAgentTree($agent_id);

        return $this->response->array(['data' => $agents]);
    }

}
