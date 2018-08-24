<?php
namespace App\Repositories\Integral\Common;

use App\Models\Access\User\UserProvider;
use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Services\Client\Account\AccountProtocol;

class CommonClass
{
    protected $repository;

    public function __construct(EloquentWalletRepository $repository)
    {
        $this->repository = $repository;
    }

    public function GetUserIntegral($userId)
    {
        return $this->repository->setUserId($userId)->getAmount(AccountProtocol::ACCOUNT_AMOUNT_INTEGRAL);
    }

    //头像
    public function GetUserPhoto($userId)
    {
       return UserProvider::query()->where('user_id','=',$userId)->first(['nickname','avatar'])->toArray();
    }
}