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
    }

    public function getRuleItems($item_keys)
    {
        $items = [];
        foreach ($item_keys as $item_key) {
            $items[] = $this->items[$item_key];
        }

        return $items;
    }
}
