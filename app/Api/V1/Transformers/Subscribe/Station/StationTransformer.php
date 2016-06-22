<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;

class StationTransformer extends TransformerAbstract
{

    public function transform(Station $station)
    {
        $data = [
            'id' => (int)$station->id,
            'name' => $station->name,
            'desc' => $station->desc,
            'address' => $station->address,
            'tel' => $station->tel,
            'phone' => $station->phone,
            'director' => $station->director,
            'cover_image' => $station->cover_image,
            'longitude' => display_coordinate($station->longitude),
            'latitude' => display_coordinate($station->latitude),
            'status' => $station->status,
        ];
        
        return $data;
    }

}
