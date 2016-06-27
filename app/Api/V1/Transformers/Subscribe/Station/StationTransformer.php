<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;

class StationTransformer extends TransformerAbstract {

    public function transform(Station $station)
    {
        $data = [
            'id' => $station['id'],
            'name' => $station['name'],
            'district_id' => $station['district_id'],
            'tel' => $station['tel'],
            'address' => $station['address'],
            'cover_image' => $station['cover_image'],
            'director' => $station['director'],
            'phone' => $station['phone'],
            'longitude' => $station['longitude'],
            'latitude' => $station['latitude'],
            'active' => $station['active'],
        ];
        if (isset($station['bind_token'])) {
            $data['bind_token'] = $station['bind_token'];
        }

        if ($station->relationLoaded('district')) {
            $data['district_name'] = $station->district->name;
        };

        return $data;
    }

}
