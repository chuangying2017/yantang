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
            'id' => $product['id'],
            'brand' => ['id' => $product['brand_id']],
            'cover_image' => $product['cover_image'],
            'title' => $product['title'],
            'sub_title' => $product['sub_title'],
            'digest' => $product['digest'],
            'price' => display_price($product['price']),
            'sales' => $product['meta']['sales'],
            'favs' => $product['meta']['favs'],
            'type' => $product['type'],
        ];

    }

    public function includeSkus(Product $product)
    {
        return $this->item($product->skus->first(), new ProductSkuTransformer(), true);
    }

}
