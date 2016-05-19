<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use JWTAuth;
use League\Fractal\TransformerAbstract;

class ProductsTransformer extends TransformerAbstract
{

    public function transform($token)
    {
        $user = JWTAuth::toUser($token);

        $data = [

        ];

        return $data;
    }

}
