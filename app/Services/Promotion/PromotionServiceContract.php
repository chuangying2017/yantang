<?php namespace App\Services\Promotion;


use App\Services\Promotion\Support\PromotionAbleItemContract;

interface PromotionServiceContract {

    public function related(PromotionAbleItemContract $items);

    public function usable(PromotionAbleItemContract $items);

    public function using(PromotionAbleItemContract $items, $rule_key);

    public function notUse(PromotionAbleItemContract $items, $rule_key);

}
