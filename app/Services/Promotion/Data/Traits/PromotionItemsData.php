<?php namespace App\Services\Promotion\Data\Traits;
trait PromotionItemsData {

    protected $items;

    protected function sumItemsAmount($items = null)
    {
        $amount = 0;
        foreach ($this->items as $item) {
            $amount = bcadd($amount, bcmul($item['price'], $item['quantity']));
        }
        return $amount;
    }

    protected function sumItemsQuantity($items = null)
    {
        $items = is_null($items) ? $this->items : $items;
        $amount = 0;
        foreach ($items as $item) {
            $amount = bcadd($amount, $item['quantity']);
        }
        return $amount;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
        foreach ($this->items as $key => $item) {
            $this->items[$key]['discount_price'] = 0;
            $this->items[$key]['rules'] = [];
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getRuleUsageItemsByKey($item_keys)
    {
        $items = [];
        foreach ($item_keys as $item_key) {
            $items[] = $this->items[$item_key];
        }

        return $items;
    }

    public function setItemsRuleBenefit($item_key, $rule_key, $discount_price, $special_price = false)
    {
        if (!in_array($item_key, $this->items[$item_key]['rules'])) {
            array_push($this->items[$item_key]['rules'], $rule_key);
            if ($special_price) {
                $this->items[$item_key]['discount_price'] = $discount_price;
            } else {
                $discount_price = $this->items[$item_key]['discount_price'] + $discount_price;
                $this->items[$item_key]['discount_price'] = $discount_price > $this->items[$item_key]['price'] ? $this->items[$item_key]['price'] : $discount_price;
            }
        }
    }

    public function addFreeItems($sku_id, $quantity)
    {
        $this->items[] = [
            'id' => $sku_id,
            'quantity' => $quantity,
            'price' => 0,
            'discount_price' => 0,
            'is_gift' => true
        ];
    }

}
