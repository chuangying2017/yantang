<?php namespace App\Api\V1\Transformers\Statement;

use App\Models\Statement\StationStatement;
use League\Fractal\TransformerAbstract;

class StationStatementTransformer extends TransformerAbstract {

    public function transform(StationStatement $statement)
    {
        if ($statement->relationLoaded('products')) {
            $this->setDefaultIncludes(['skus']);
        }

        return [
            'statement_no' => $statement['statement_no'],
            'station_id' => $statement['merchant_id'],
            'year' => $statement['year'],
            'month' => $statement['month'],
            'settle_amount' => display_price($statement['settle_amount']),
            'service_amount' => display_price($statement['service_amount']),
            'total_amount' => display_price($statement['total_amount']),
            'status' => $statement['status'],
            'confirm_at' => $statement['confirm_at'],
            'memo' => $statement['memo'],
            'system' => [
                'operator' => $statement['operator'],
                'memo' => $statement['memo'],
                'confirm_at' => $statement['confirm_at'],
                'status' => $statement['status'],
            ],
        ];
    }

    public function includeSkus(StationStatement $statement)
    {
        return $this->collection($statement->products, new StatementSkuTransformer(), true);
    }

}
