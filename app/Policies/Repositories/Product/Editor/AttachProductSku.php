<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;

class AttachProductSku extends EditorAbstract {

	/**
     * AttachProductSku constructor.
     * @param ProductSkuRepositoryContract $productSkuRepository
     */
    public function __construct(ProductSkuRepositoryContract $productSkuRepository)
    {
        $this->productSkuRepository = $productSkuRepository;
    }

    public function handle(array $product_data, Product $product)
    {
        foreach ($product_data['skus'] as $sku) {
            $product->skus[] = $this->productSkuRepository->createSku($sku, $product['id']);
        }

        return $this->next($product_data, $product);
    }


}
