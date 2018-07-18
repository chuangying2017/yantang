<?php
namespace App\Repositories\Integral\Decorate;

use App\Models\Integral\Product;
use App\Repositories\Integral\Editor\EditorAbstract;

class ProductAttribute extends EditorAbstract
{
        public function handle(array $data, Product $product)
        {
            $product->specification()->sync($data['product_attr']);

             return $this->next($data,$product);
        }
}