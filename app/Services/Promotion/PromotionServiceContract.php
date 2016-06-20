<?php namespace App\Services\Promotion;


use App\Services\Promotion\Support\PromotionAbleItemContract;

interface PromotionServiceContract {

    public function related(PromotionAbleItemContract $items, $rules = null);

    public function usable(PromotionAbleItemContract $items);

    public function using(PromotionAbleItemContract $items, $rule_key);

    public function notUsing(PromotionAbleItemContract $items, $rule_key);

}
