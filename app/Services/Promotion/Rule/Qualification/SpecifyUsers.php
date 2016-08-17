<?php namespace App\Services\Promotion\Rule\Qualification;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class SpecifyUsers implements Qualification{

    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        return in_array($user->getUserId(), to_array($qualify_values));
    }
}
