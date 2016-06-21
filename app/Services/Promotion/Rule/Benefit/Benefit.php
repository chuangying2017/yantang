<?php namespace App\Services\Promotion\Rule\Benefit;

use App\Services\Promotion\Support\PromotionAbleItemContract;

interface Benefit {

    public function calAndSet($mode, $value, PromotionAbleItemContract $items, $item_option =null);

    public function rollback($mode, $benefit_value, PromotionAbleItemContract $items, $item_option = null);

}
