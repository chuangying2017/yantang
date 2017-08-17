<?php namespace App\Api\V1\Transformers;

use App\Models\Residence;
use League\Fractal\TransformerAbstract;

class ResidenceTransformer extends TransformerAbstract {

    public function transform(Residence $station)
    {
        $data = [
            'id' => $station['id'],
            'name' => $station['name'],
            'aliases' => $station['aliases'],
            'goal' => $station['goal'],
        ];

        return $data;
    }
}
