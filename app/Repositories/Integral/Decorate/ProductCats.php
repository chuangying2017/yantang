<?php
namespace App\Repositories\Integral\Decorate;

use App\Models\Integral\Product;
use App\Repositories\Integral\Editor\EditorAbstract;

class ProductCats extends EditorAbstract
{
    public function handle(array $data, Product $product)
    {
        $product->integral_category()->sync(['category_id'=>$data['category_id']]);

        return $this->next($data,$product);
    }
}