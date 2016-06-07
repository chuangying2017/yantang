<?php namespace App\Services\Order\Generator;

use App\Repositories\Auth\User\UserContract;

class GetUserInfo extends GenerateHandlerAbstract {

    /**
     * @var UserContract
     */
    private $userContract;

    /**
     * GetUserInfo constructor.
     * @param UserContract $userContract
     */
    public function __construct(UserContract $userContract)
    {
        $this->userContract = $userContract;
    }

    public function handle(TempOrder $temp_order)
    {
        $temp_order->setUser($this->userContract->getUserInfo($temp_order->getUser()));
        return $this->next($temp_order);
    }
}
