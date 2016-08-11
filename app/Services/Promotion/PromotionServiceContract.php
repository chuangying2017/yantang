<?php namespace App\Services\Promotion;


use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

interface PromotionServiceContract {

    public function setItems(PromotionAbleItemContract $items);

    public function setUser(PromotionAbleUserContract $user);

    public function checkRelated($rules = null);

    public function checkUsable();

    public function setUsing($rule_key);

    public function setNotUsing($rule_key);

}
