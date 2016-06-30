<?php namespace App\Api\V1\Transformers\Statement;

use App\Models\Statement\StoreStatement;
use League\Fractal\TransformerAbstract;

class StoreStatementTransformer extends TransformerAbstract {

    public function transform(StoreStatement $statement)
    {
        if ($statement->relationLoaded('products')) {
            $this->setDefaultIncludes(['skus']);
        }

        return [
            'statement_no' => $statement['statement_no'],
            'store_id' => $statement['merchant_id'],
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

    public function includeSkus(StoreStatement $statement)
    {
        return $this->collection($statement->products, new StatementSkuTransformer(), true);
    }

}
