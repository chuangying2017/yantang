<?php namespace App\Services\Promotion;


use App\Services\Promotion\Support\PromotionAbleItemContract;

interface PromotionServiceContract {

    public function setItems(PromotionAbleItemContract $items);

    public function related($rules = null);

    public function usable();

    public function using($rule_key);

    public function notUsing($rule_key);

}
