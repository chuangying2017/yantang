<?php namespace App\Api\V1\Transformers\Backend\Station;

use JWTAuth;
use League\Fractal\TransformerAbstract;

class ProductsTransformer extends TransformerAbstract
{

    /**
     * @param $token
     * @return array
     */
    public function transform($token)
    {
        $user = JWTAuth::toUser($token);

        $data = [

        ];

        return $data;
    }

}
