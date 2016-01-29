<?php namespace App\Services\Agent;

use App\Models\AgentInfo;

class AgentApplyRepository {

    public static function storeApplyInfo($user_id, $data)
    {

        $apply_info = array_only($data, [
            'parent_agent_id',
            'apply_agent_id',
            'bank_no',
            'agent_role',
            'bank_detail',
            'name',
            'director_name',
            'phone',
            'email',
            'license_image',
            'id_image',
            'office_image',
            'contract_image',
        ]);
        $apply_info['status'] = AgentProtocol::APPLY_STATUS_OF_PENDING;
        $apply_info['user_id'] = $user_id;

        return AgentInfo::updateOrCreate(
            ['user_id' => $user_id],
            $apply_info
        );

    }

    public static function byUser($user_id)
    {
        $agent_info = AgentInfo::where('user_id', $user_id)->first();

        if ($agent_info->apply_agent_id) {
            $agent_info->agents = AgentRepository::getAgentUpTree($agent_info['apply_agent_id']);
        } else {
            $agent_info->agents = AgentRepository::getAgentUpTree($agent_info['parent_agent_id']);
        }

        return $agent_info;
    }

    public static function byAgent($agent_id, $status = AgentProtocol::APPLY_STATUS_OF_PENDING, $paginate = 20)
    {
        $query = AgentInfo::where('parent_agent_id', $agent_id)->where('status', $status);
        if ( ! is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public static function byId($apply_id)
    {
        if ($apply_id instanceof AgentInfo) {
            return $apply_id;
        }

        return AgentInfo::findOrFail($apply_id);
    }

    public static function updateStatus($apply_id, $status)
    {
        return AgentInfo::where('id', $apply_id)->update(['status' => $status]);
    }


}
