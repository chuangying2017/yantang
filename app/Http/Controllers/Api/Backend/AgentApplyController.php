<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Requests\ApplyAgentRequest;
use App\Http\Transformers\AgentInfoTransformer;
use App\Services\Agent\AgentProtocol;
use App\Services\Agent\AgentService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Toplan\PhpSms\Agent;

class AgentApplyController extends Controller {

    public function index(Request $request)
    {
        $user_id = $this->getCurrentAuthUserId();

        $status = $request->input('status') ?: AgentProtocol::APPLY_STATUS_OF_PENDING;

        $apply = AgentService::listsAgentApplyByUser($user_id, $status);

//        return $this->response->paginator($apply, new AgentInfoTransformer());

        return $this->response->array(['data' => $apply]);
    }

    public function show($apply_id)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $apply = AgentService::getApplyById($apply_id, $user_id);

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Request $request, $apply_id)
    {
        try {
            $user_id = get_current_auth_user_id();
            $action = $request->input('action', AgentProtocol::APPLY_STATUS_OF_APPROVE);
            $memo = $request->input('memo', '其他');

            if ($action == AgentProtocol::APPLY_STATUS_OF_APPROVE) {
                $agent = AgentService::approveApply($apply_id, $user_id);
            } else if ($action == AgentProtocol::APPLY_STATUS_OF_REJECT) {
                $agent = AgentService::rejectApply($apply_id, $user_id, $memo);
            }

            return $this->response->array(['data' => $agent]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

    public function updateInfo(Request $request, $apply_id)
    {
        try {
            $data = $request->all();

            $apply = AgentService::updateApplyInfo($apply_id, $data);

            return $this->response->array(['data' => $apply]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

}
