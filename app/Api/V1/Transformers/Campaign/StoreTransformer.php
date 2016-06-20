<?php namespace App\Api\V1\Transformers\Campaign;

use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpKernel\HttpCache\Store;

class StoreTransformer extends TransformerAbstract {

    public function transform(Store $store)
    {
        return [
            'name' => $store['name'],
            'user_id' => $store['user_id'],
            'address' => $store['address'],
            'cover_image' => $store['cover_image'],
            'director' => $store['director'],
            'phone' => $store['phone'],
            'longitude' => $store['longitude'],
            'latitude' => $store['latitude'],
            'active' => $store['active']
        ];
    }
}
