<?php namespace App\Http\Transformers;

use App\Models\Address;
use App\Models\Agent;
use App\Models\AgentInfo;
use League\Fractal\TransformerAbstract;

class AgentTransformer extends TransformerAbstract {

    public function transform(Agent $agent)
    {

        return [
            'id'   => (int)$agent->id,
            'mark' => $agent->mark,
            'name' => $agent->name,
            'no'   => $agent->no,
        ];
    }


}
