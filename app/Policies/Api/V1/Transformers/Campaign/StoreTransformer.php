<?php namespace App\Api\V1\Transformers\Campaign;

use App\Models\Store;
use League\Fractal\TransformerAbstract;

class StoreTransformer extends TransformerAbstract {

    public function transform(Store $store)
    {
        $data = [
            'id' => $store['id'],
            'name' => $store['name'],
            'address' => $store['address'],
            'cover_image' => $store['cover_image'],
            'director' => $store['director'],
            'phone' => $store['phone'],
            'longitude' => $store['longitude'],
            'latitude' => $store['latitude'],
            'active' => $store['active'],
        ];
        if (isset($store['bind_token'])) {
            $data['bind_token'] = $store['bind_token'];
        }

        return $data;
    }
}
