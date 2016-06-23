<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\StatementsProduct;

class StatementsProductTransformer extends TransformerAbstract
{

    public function transform(StatementsProduct $statementsProduct)
    {
        $data = [
            'id' => (int)$statementsProduct->id,
            'statements_id' => $statementsProduct->statements_id,
            'name' => $statementsProduct->name,
            'settle_price' => $statementsProduct->settle_price,
            'service_fee' => $statementsProduct->service_fee,
            'quantity' => $statementsProduct->quantity,
        ];

        return $data;
    }

}
