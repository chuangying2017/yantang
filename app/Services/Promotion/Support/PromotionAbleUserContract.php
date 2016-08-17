<?php namespace App\Services\Promotion\Support;

use App\Models\Access\User\User;

interface PromotionAbleUserContract {

    public function setUser(User $user);

    public function getUserId();

    public function getUserLevel();

    public function getUserRoles();

    public function getGroup();

}
