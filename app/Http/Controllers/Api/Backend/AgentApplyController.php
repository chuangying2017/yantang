<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Services\Agent\AgentService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AgentApplyController extends Controller {

    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();
        $apply = AgentService::userApply($user_id);

        return $this->response->apply(['data' => $apply]);
    }

    //申请成为代理商
    public function store(ApplyAgentRequest $request)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $apply = AgentService::newApply($user_id, $request->all());

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

}
