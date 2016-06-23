<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Statements;
use App\Services\Subscribe\SubscribeProtocol;

class StatementsTransformer extends TransformerAbstract
{

    public function transform(Statements $statements)
    {
        if (isset($statements->detail) && $statements->detail) {
            $this->setDefaultIncludes(['product']);
        }
        $data = [
            'id' => (int)$statements->id,
            'station_id' => $statements->station_id,
            'statement_no' => $statements->address,
            'year' => $statements->year,
            'month' => $statements->month,
            'settle_amount' => $statements->settle_amount,
            'service_amount' => $statements->settle_amount,
            'status' => $statements->status,
            'status_name' => SubscribeProtocol::statements_status($statements->status),
        ];

        return $data;
    }

    public function includeProduct(Statements $statements)
    {
        $product = $statements->product;
        return $this->collection($product, new StatementsProductTransformer());
    }

}
