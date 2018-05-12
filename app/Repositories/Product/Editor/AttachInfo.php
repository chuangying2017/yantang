<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Models\Product\ProductInfo;
# 附加信息
class AttachInfo extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
        $product->info()->create([
            'attr' => array_get($product_data, 'attr', ''),
            'tags' => array_get($product_data, 'tags', ''),
            'detail' => array_get($product_data, 'detail', ''),
        ]);

        return $this->next($product_data, $product);
    }


}
