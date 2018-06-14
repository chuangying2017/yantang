<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use App\Api\V1\Transformers\Counter\CounterTransformer;
use App\Models\Counter\Counter;
use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;

class StationTransformer extends TransformerAbstract {

    protected $show_geo;

    public function __construct($show_geo = true)
    {
        $this->show_geo = $show_geo;
    }

    public function transform(Station $station)
    {
        if ($station->relationLoaded('user')) {
            $this->defaultIncludes[] = 'user';
        }

        if ($station->relationLoaded('counter')) {
            $this->defaultIncludes[] = 'counter';
        }

        $data = [
            'id' => $station['id'],
            'name' => $station['name'],
            'merchant_no' => $station['merchant_no'],
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

        if ($this->show_geo) {
            $data['geo'] = $station['geo'];
        }

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

    public function includeCounter(Station $station)
    {
        if ($station['counter']) {
            return $this->item($station['counter'], new CounterTransformer(), true);
        }

        return null;
    }
    

}
