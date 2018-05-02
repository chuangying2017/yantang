<?php namespace App\Services\Product\Listeners;

use App\Repositories\Search\Item\ProductSearchRepository;

class ProductSearchObserver {

    private $productSearchRepository;

    /**
     * ProductObserver constructor.
     * @param $app
     */
    public function __construct(ProductSearchRepository $productSearchRepository)
    {
        $this->productSearchRepository = $productSearchRepository;
    }

    public function created($product)
    {
        $this->productSearchRepository->create($product);
    }

    public function updated($product)
    {
        $this->productSearchRepository->update($product);
    }

    public function restored($product)
    {
        $this->productSearchRepository->create($product);
    }

    public function deleted($product)
    {
        $this->productSearchRepository->delete($product);
    }

}
