<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

class UpdateMeta extends EditorAbstract {

    public function handle(array $product_data, Product $product)
    {
        $product->meta()->update([
            'stock' => $this->sumStock($product_data['skus']),
            'favs' => 0,
            'sales' => 0
        ]);

        return $this->next($product_data, $product);
    }

    protected function sumStock($skus)
    {
        $total = 0;
        foreach ($skus as $sku) {
            $total += $sku['stock'];
        }
        return $total;
    }
}
