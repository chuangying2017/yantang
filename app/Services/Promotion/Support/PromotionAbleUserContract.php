<?php namespace App\Services\Promotion\Support;

interface PromotionAbleUserContract {

    public function setUser($user);

    public function getUserId();

    public function getUserLevel();

    public function getUserRoles();

    public function getGroup();

}
