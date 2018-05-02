<?php namespace App\Services\Promotion\Rule\Usage;

use App\Repositories\Product\Cat\EloquentCategoryRepository;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class CategoryUsage implements Usage {

    /**
     * @var EloquentCategoryRepository
     */
    private $categoryRepo;

    /**
     * CategoryUsage constructor.
     * @param EloquentCategoryRepository $categoryRepo
     */
    public function __construct(EloquentCategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function filter(PromotionAbleItemContract $items, $item_values)
    {
        $item_keys = [];
        foreach ($items->getItems() as $key => $item) {
            $cat_ids = $this->categoryRepo->getIdsByProducts($item['product_id']);
            if (count(array_diff($cat_ids, $item_values)) < count($cat_ids)) {
                $item_keys[] = $key;
            }
        }

        return $item_keys;
    }

}
