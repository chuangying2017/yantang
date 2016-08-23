<?php namespace App\Services\Promotion\Rule\Usage;
use App\Services\Promotion\Support\PromotionAbleItemContract;

interface Usage {

    public function filter(PromotionAbleItemContract $items, $item_values);

}
