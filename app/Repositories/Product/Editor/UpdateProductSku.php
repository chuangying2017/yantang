<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;

class UpdateProductSku extends EditorAbstract {

    protected $productSkuRepository;


	/**
     * UpdateProductSku constructor.
     * @param ProductSkuRepositoryContract $productSkuRepository
     */
    public function __construct(ProductSkuRepositoryContract $productSkuRepository)
    {
        $this->productSkuRepository = $productSkuRepository;
    }

    public function handle(array $product_data, Product $product)
    {
        $this->productSkuRepository->updateSkusOfProduct($product['id'], $product_data['skus']);

        $product->load('skus');
        
        return $this->next($product_data, $product);
    }



}
