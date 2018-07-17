<?php
namespace App\Repositories\Integral\Decorate;

use App\Models\Integral\Product;
use App\Repositories\Integral\Editor\EditorAbstract;

class Images extends EditorAbstract
{

    public function handle(array $data, Product $product)
    {
        $product->images()->sync($data['image_ids']);

        return $this->next($data, $product);
    }
}