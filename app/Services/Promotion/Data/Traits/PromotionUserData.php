<?php namespace App\Services\Promotion\Data\Traits;
trait PromotionUserData {

    protected $user;

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

}
