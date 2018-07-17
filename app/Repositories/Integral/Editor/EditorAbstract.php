<?php
namespace App\Repositories\Integral\Editor;
use App\Models\Integral\Product;

abstract class EditorAbstract
{
    protected $editor;

    abstract public function handle(array $data, Product $product);

    public function editWith(EditorAbstract $editorAbstract)
    {
        $this->editor=$editorAbstract;
    }

    public function next($product_data, $product)
    {

        if($this->editor){
            $this->editor->handle($product_data, $product);
        }

        return compact('product_data', 'product');
    }
}