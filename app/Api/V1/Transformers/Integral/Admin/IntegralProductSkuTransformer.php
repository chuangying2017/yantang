<?php

namespace App\Api\V1\Transformers\Integral\Admin;

use App\App\Models\Integral\ProductSku;
use League\Fractal\TransformerAbstract;

class IntegralProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $productSku)
    {
            return [

            ];
    }
}
