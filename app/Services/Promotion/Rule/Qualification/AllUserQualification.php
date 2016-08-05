<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Services\Promotion\Support\PromotionAbleUserContract;

class AllUserQualification implements Qualification {

    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        return true;
    }
}
