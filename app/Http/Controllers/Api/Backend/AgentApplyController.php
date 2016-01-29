<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Services\Agent\AgentProtocol;
use App\Services\Agent\AgentService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Toplan\PhpSms\Agent;

class AgentApplyController extends Controller {

    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();

        $apply = AgentService::needCheckAgentApplyByUser($user_id);

        return $this->response->array(['data' => $apply]);
    }

    public function show($apply_id)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $apply = AgentService::getApplyById($apply_id, $user_id);

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, $apply_id)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $action = $request->input('action', AgentProtocol::APPLY_STATUS_OF_APPROVE);

            if ($action == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
                $agent = AgentService::approveApply($apply_id, $user_id);
            } else if ($action == AgentProtocol::APPLY_STATUS_OF_REJECT) {
                $agent = AgentService::rejectApply($apply_id, $user_id);
            }

            return $this->response->array(['data' => $agent]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

}
