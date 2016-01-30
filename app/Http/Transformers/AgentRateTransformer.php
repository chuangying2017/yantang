<?php namespace App\Http\Transformers;

use App\Models\Address;
use App\Models\AgentRate;
use League\Fractal\TransformerAbstract;

class AgentRateTransformer extends TransformerAbstract {

    public function transform(AgentRate $rate)
    {
        return [
            'id'    => (int)$rate->id,
            'name'  => $rate->name,
            'level' => $rate->level,
            'rate'  => display_percentage($rate->rate),
        ];
    }
}
