<?php namespace App\Http\Transformers;

use App\Models\ProductCollection;
use League\Fractal\TransformerAbstract;

class FavTransformer extends TransformerAbstract {

    public function transform(ProductCollection $fav)
    {
        $this->setDefaultIncludes(['product']);

        return [
            'id'         => (int)$fav->id,
            'product_id' => (int)$fav->product_id
        ];
    }

    public function includeProduct(ProductCollection $fav)
    {
        $product = $fav->product;

        return $this->item($product, new ProductTransformer());
    }
}
