<?php namespace App\Api\V1\Transformers;

use App\Models\Residence;
use League\Fractal\TransformerAbstract;

class ResidenceTransformer extends TransformerAbstract {

    public function transform(Residence $residence)
    {
        $data = [
            'id' => $residence['id'],
            'district' => $residence['district']['name'],
            'name' => $residence['name'],
            'aliases' => $residence['aliases'],
            'goal' => $residence['goal'],
            'complete' => $residence['complete'],
        ];

        return $data;
    }
}
