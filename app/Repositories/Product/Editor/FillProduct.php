<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use Carbon\Carbon;

class FillProduct extends ProductEditor {

    public function handle(array $product_data, Product $product)
    {
        return $this->next($product_data, $product->save($this->filterMain($product_data)));
    }

    protected function filterMain($product_data)
    {
        return [
            'product_no' => uniqid('pn_'),
            'brand_id' => $product_data['brand_id'],
            'merchant_id' => $product_data['merchant_id'],
            'title' => $product_data['title'],
            'sub_title' => $product_data['sub_title'],
            'digest' => $product_data['digest'],
            'cover_image' => $product_data['cover_image'],
            'price' => $product_data['price'],
            'status' => $product_data['status'],
            'type' => $product_data['type'],
            'open_time' => $product_data['open_time'] ?: Carbon::now(),
            'end_time' => $product_data['end_time'] ?: Carbon::now()->addYears(50),
            'with_invoice' => $product_data['with_invoice'],
            'with_care' => $product_data['with_care'],
        ];
    }

}
