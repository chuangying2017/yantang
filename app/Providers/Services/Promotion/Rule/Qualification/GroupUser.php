<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Services\Promotion\Support\PromotionAbleUserContract;

class GroupUser implements Qualification {

    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        $require_group = to_array($qualify_values);
        if (count(array_diff($require_group, $user->getGroup())) == count($require_group)) {
            return false;
        }
        return true;
    }
}
