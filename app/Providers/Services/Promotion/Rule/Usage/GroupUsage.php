<?php namespace App\Services\Promotion\Rule\Usage;

use App\Repositories\Product\Group\EloquentGroupRepository;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class GroupUsage implements Usage {


    /**
     * @var EloquentGroupRepository
     */
    protected $groupRepo;

    /**
     * GroupUsage constructor.
     * @param EloquentGroupRepository $groupRepo
     */
    public function __construct(EloquentGroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    public function filter(PromotionAbleItemContract $items, $item_values)
    {
        $item_keys = [];
        foreach ($items->getItems() as $key => $item) {
            $cat_ids = $this->groupRepo->getIdsByProducts($item['product_id']);
            if (count(array_diff($cat_ids, $item_values)) < count($cat_ids)) {
                $item_keys[] = $key;
            }
        }

        return $item_keys;
    }


}
