<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class TransPrice extends ProductEditor {

    protected $price = null;

    public function handle(array $product_data, Product $product)
    {
        foreach ($product_data as $key => $sku) {
            $product_data['skus'] = array_map("filterPrice", $product_data['skus']);
        }

        $product_data['price'] = $this->price;

        return $this->next($product_data, $product);
    }

    /**
     * @return \Closure
     */
    public function filterPrice($sku)
    {
        $sku['display_price'] = store_price(array_get($sku, 'display_price', 0));
        $sku['price'] = store_price(array_get($sku, 'price', 0));
        $sku['express_fee'] = store_price(array_get($sku, 'express_fee', 0));
        $sku['income_price'] = store_price(array_get($sku, 'income_price', 0));
        $sku['settle_price'] = store_price(array_get($sku, 'settle_price', 0));

        if (is_null($this->price) || $this->price > $sku['price']) {
            $this->price = $sku['price'];
        }

        return $sku;
    }

}
