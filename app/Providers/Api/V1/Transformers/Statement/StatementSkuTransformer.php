<?php namespace App\Api\V1\Transformers\Statement;

use App\Models\Statement\StatementProduct;
use League\Fractal\TransformerAbstract;

class StatementSkuTransformer extends TransformerAbstract {

    public function transform(StatementProduct $sku)
    {
        return [
            'statement_no' => $sku['statement_no'],
            'product_id' => $sku['product_id'],
            'product_sku_id' => $sku['product_sku_id'],
            'name' => $sku['name'],
            'price' => display_price($sku['price']),
            'service_fee' => display_price($sku['service_fee']),
            'quantity' => $sku['quantity'],
            'total_amount' => display_price($sku['total_amount']),
        ];
    }
}
