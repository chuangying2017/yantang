<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;

abstract class EditorAbstract {

    protected $editor;

    public abstract function handle(array $product_data, Product $product);

    public function editWith(EditorAbstract $editor)
    {
        $this->editor = $editor;
    }

    public function next($product_data, $product)
    {
        if ($this->editor) {
            $this->editor->handle($product_data, $product);
        }

        return compact('product_data', 'product');
    }


}
