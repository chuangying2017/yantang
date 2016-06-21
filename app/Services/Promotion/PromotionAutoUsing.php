<?php namespace App\Services\Promotion;

use App\Services\Promotion\Support\PromotionAbleItemContract;

interface PromotionAutoUsing {

    public function autoUsing(PromotionAbleItemContract $items);

}
