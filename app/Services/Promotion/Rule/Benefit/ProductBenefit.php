<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class ProductBenefit extends Benefit {

    /**
     * ProductBenefit constructor.
     * @param ProductSkuRepositoryContract $skuRepo
     */
    public function __construct(ProductSkuRepositoryContract $skuRepo)
    {
        $this->skuRepo = $skuRepo;
    }

    public function cal($mode, $value, PromotionAbleItemContract $items, $item_option = null)
    {
        $value = is_object($value) ? json_decode(json_encode($value), true) : $value;
        $skus = $this->skuRepo->getSkus(array_keys($value));

        foreach ($skus as $key => $sku) {
            $quantity = $value[$sku['id']];
            $skus[$key]['quantity'] = $quantity;
            $skus[$key]['total_amount'] = $quantity * $sku[$items->getSkuPriceTag()];
            $skus[$key]['discount_amount'] = $skus[$key]['total_amount'];
            $skus[$key]['pay_amount'] = 0;
        }
        
        $benefit_values = $skus;
    
        return $benefit_values;
    }

    public function rollback($mode, $benefit_value, PromotionAbleItemContract $items, $item_option = null)
    {
        // TODO: Implement rollback() method.
    }

    /**
     * @var ProductSkuRepositoryContract
     */
    private $skuRepo;
}
