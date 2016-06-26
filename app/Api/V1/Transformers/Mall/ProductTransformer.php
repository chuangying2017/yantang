<?php namespace App\Api\V1\Transformers\Mall;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Product\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus'];

    public function transform(Product $product)
    {

        $this->setInclude($product);

        return [
            'id' => $product['id'],
            'brand' => ['id' => $product['brand_id']],
            'product_no' => $product['product_no'],
            'title' => $product['title'],
            'sub_title' => $product['sub_title'],
            'digest' => $product['digest'],
            'cover_image' => $product['cover_image'],
            'price' => $product['price'],
            'status' => $product['status'],
            'type' => $product['type'],
            'open_time' => $product['open_time'],
            'end_time' => $product['end_time'],
        ];
    }

    public function includeSkus(Product $product)
    {
        return $this->item($product->skus->first(), new ProductSkuTransformer(), true);
    }

}
