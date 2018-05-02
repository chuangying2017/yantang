<?php namespace App\Api\V1\Transformers\Admin\Client;
use App\Models\Client\Client;
use League\Fractal\TransformerAbstract;

class ClientUserTransformer extends TransformerAbstract{

    public function transform(Client $client)
    {
        return $client->toArray();
    }

}
