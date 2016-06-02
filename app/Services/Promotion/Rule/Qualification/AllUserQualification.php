<?php namespace App\Services\Promotion\Rule\Qualification;
class AllUserQualification implements  Qualification {

    public function check($user, $qualify_values)
    {
        return true;
    }
}
