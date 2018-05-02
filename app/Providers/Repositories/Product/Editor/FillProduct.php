<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\ProductProtocol;
use Carbon\Carbon;

class FillProduct extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
        $product->fill($this->filterMain($product_data))->save();
        return $this->next($product_data, $product);
    }

    protected function filterMain($product_data)
    {
        return [
            'product_no' => uniqid('pn_'),
            'brand_id' => array_get($product_data, 'brand_id', 0),
            'merchant_id' => array_get($product_data, 'merchant_id', 0),
            'title' => $product_data['title'],
            'sub_title' => array_get($product_data, 'sub_title', ''),
            'digest' => array_get($product_data, 'digest', ''),
            'cover_image' => array_get($product_data, 'cover_image', ''),
            'price' => $product_data['price'],
            'status' => array_get($product_data, 'status', ProductProtocol::VAR_PRODUCT_STATUS_UP),
            'type' => array_get($product_data, 'type', ProductProtocol::TYPE_OF_ENTITY),
            'open_time' => array_get($product_data, 'open_time', Carbon::now()),
            'end_time' => array_get($product_data, 'end_time', Carbon::now()->addYears(50)),
            'with_invoice' => array_get($product_data, 'with_invoice', 0),
            'with_care' => array_get($product_data, 'with_care', 0),
            'priority' => array_get($product_data, 'priority', 0),
        ];
    }

}
