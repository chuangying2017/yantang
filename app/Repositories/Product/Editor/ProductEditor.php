<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

abstract class ProductEditor {

    protected $editor;

    public abstract function handle(array $product_data, Product $product);

    public function editWith(ProductEditor $editor)
    {
        $this->editor = $editor;
    }

    public function next($product_data, $product)
    {
        if ($this->editor) {
            $this->editor->handle($product_data, $product);
        }

        return [$product_data, $product];
    }


}
