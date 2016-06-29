<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use App\Models\District;
use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract {

    public function transform(District $district)
    {
        return [
            'id' => $district['id'],
            'name' => $district['name'],
            'station_count' => $district['station_count'],
        ];
    }

}
