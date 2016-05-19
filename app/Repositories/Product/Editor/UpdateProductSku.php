<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;

class UpdateProductSku extends EditorAbstract {

    public function __construct(ProductSkuRepositoryContract $productSkuRepository)
    {
        $this->productSkuRepository = $productSkuRepository;
    }

    public function handle(array $product_data, Product $product)
    {
        $product->skus = $this->productSkuRepository->updateSkusOfProduct($product['id'], $product_data['skus']);

        return $this->next($product_data, $product);
    }


}
