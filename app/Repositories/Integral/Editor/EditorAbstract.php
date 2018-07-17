<?php
namespace App\Repositories\Integral\Editor;
use App\Services\Integral\Product\ProductInerface;
abstract class EditorAbstract
{
    protected $editor;

    abstract public function handle(array $data, ProductInerface $product);

    public function EditorWith(EditorAbstract $editorAbstract)
    {
        $this->editor=$editorAbstract;
    }

    public function next()
    {

    }
}