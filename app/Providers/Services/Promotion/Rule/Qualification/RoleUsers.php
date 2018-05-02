<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Services\Promotion\Support\PromotionAbleUserContract;

class RoleUsers implements Qualification {

    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        $require_roles = to_array($qualify_values);
        foreach ($user->getUserRoles() as $role) {
            if (in_array($role['id'], $require_roles)) {
                return true;
            }
        }

        return false;
    }
}
