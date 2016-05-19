<?php namespace App\Api\V1\Transformers\Admin\Product;


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
            'title' => $product['title']
        ];

    }

    public function includeSkus(Product $product)
    {
        return $this->collection($product->skus, new ProductSkuTransformer(), true);
    }

}
