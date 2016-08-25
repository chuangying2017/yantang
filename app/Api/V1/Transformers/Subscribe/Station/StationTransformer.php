<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;

class StationTransformer extends TransformerAbstract {

    public function transform(Station $station)
    {
        if ($station->relationLoaded('user')) {
            $this->defaultIncludes = ['user'];
        }

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
            'geo' => $station['geo'],
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

    public function includeUser(Station $station)
    {
        return $this->collection($station->user, new StationUserTransformer(), true);
    }

}
