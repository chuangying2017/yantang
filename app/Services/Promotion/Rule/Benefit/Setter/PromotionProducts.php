<?php namespace App\Services\Promotion\Rule\Benefit\Setter;
class PromotionProducts implements PromotionAbleItemBenefitContract {

    protected $skus;

    public function init($benefit_name)
    {
        $this->skus = $benefit_name;
        return $this;
    }

    public function add($skus, $key = null)
    {
        foreach ($skus as $sku) {
            foreach ($this->skus as $sku_key => $origin_sku) {
                if ($origin_sku['id'] == $sku['id']) {
                    $this->skus[$sku_key]['quantity'] += $sku['quantity'];
                    $this->skus[$sku_key]['discount_amount'] += $sku['discount_amount'];
                    break;
                }
                $this->skus[] = $sku;
            }
        }
    }

    public function remove($skus, $key = null)
    {
        foreach ($skus as $sku) {
            foreach ($this->skus as $sku_key => $origin_sku) {
                if ($origin_sku['id'] == $sku['id']) {
                    if ($sku['quantity'] < $origin_sku['quantity']) {
                        $this->skus[$sku_key]['quantity'] -= $sku['quantity'];
                        $this->skus[$sku_key]['discount_amount'] -= $sku['discount_amount'];
                    } else {
                        unset($this->skus[$sku_key]);
                    }
                    break;
                }
            }
        }
    }

    public function get($key = null)
    {
        // TODO: Implement get() method.
    }

}
