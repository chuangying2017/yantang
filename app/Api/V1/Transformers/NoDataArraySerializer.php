<?php namespace App\Api\V1\Transformers;

use League\Fractal\Serializer\ArraySerializer;

class NoDataArraySerializer extends ArraySerializer {

    /**
     * Serialize a collection.
     */
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey) ? $data : ['data' => $data];
    }

    /**
     * Serialize an item.
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? $data : ['data' => $data];
    }

}
