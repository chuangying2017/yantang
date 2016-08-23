<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Services\Promotion\Support\PromotionAbleUserContract;

interface Qualification {
    
    public function check(PromotionAbleUserContract $user, $qualify_values);

}
