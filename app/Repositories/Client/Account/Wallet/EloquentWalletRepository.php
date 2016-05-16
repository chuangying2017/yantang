<?php namespace App\Repositories\Client\Account\Wallet;
use App\Models\Account\Wallet;
use App\Models\Account\WalletRecord;
use App\Repositories\Client\Account\EloquentAccountRepository;

class EloquentWalletRepository extends EloquentAccountRepository implements WalletRepositoryContract{

    protected function init()
    {
        $this->setAccountModel(Wallet::class);
        $this->setAccountRecordModel(WalletRecord::class);
    }

}
