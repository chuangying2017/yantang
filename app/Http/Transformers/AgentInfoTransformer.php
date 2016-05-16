<?php namespace App\Http\Transformers;

use App\Models\Client\Address;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Services\Agent\AgentProtocol;
use League\Fractal\TransformerAbstract;

class AgentInfoTransformer extends TransformerAbstract {

    public function transform(AgentInfo $agent_info)
    {
        $this->setDefaultIncludes(['agent']);

        return [
            'id'              => (int)$agent_info->id,
            'user_id'         => $agent_info->user_id,
            'status'          => $agent_info->status,
            'bank_no'         => $agent_info->bank_no,
            'bank_detail'     => $agent_info->bank_detail,
            'apply_agent_id'  => $agent_info->apply_agent_id,
            'parent_agent_id' => $agent_info->parent_agent_id,
            'agent_role'      => $agent_info->agent_role,
            'agent_role_name' => AgentProtocol::name($agent_info->agent_role),
            'name'            => $agent_info->name,
            'director_name'   => $agent_info->director_name,
            'phone'           => $agent_info->phone,
            'email'           => $agent_info->email,
            'license_image'   => $agent_info->license_image,
            'id_image'        => $agent_info->id_image,
            'office_image'    => $agent_info->office_image,
            'contract_image'  => $agent_info->contract_image,
        ];
    }

    public function includeAgent(AgentInfo $agentInfo)
    {
        $agent = $agentInfo->agent ? : new Agent(['id' => null]);

        return $this->item($agent, new AgentTransformer());
    }


}
