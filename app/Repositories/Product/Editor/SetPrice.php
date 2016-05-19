<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class SetPrice extends EditorAbstract {

    protected $price = null;

    public function handle(array $product_data, Product $product)
    {
        foreach ($product_data['skus'] as $key => $sku) {
            $product_data['skus'][$key]['display_price'] = store_price(array_get($sku, 'display_price', 0));
            $product_data['skus'][$key]['price'] = store_price(array_get($sku, 'price', 0));
            $product_data['skus'][$key]['express_fee'] = store_price(array_get($sku, 'express_fee', 0));
            $product_data['skus'][$key]['income_price'] = store_price(array_get($sku, 'income_price', 0));
            $product_data['skus'][$key]['settle_price'] = store_price(array_get($sku, 'settle_price', 0));

            if (is_null($this->price) || $this->price > $sku['price']) {
                $this->price = $sku['price'];
            }
        }

        $product_data['price'] = $this->price;

        return $this->next($product_data, $product);
    }


}
