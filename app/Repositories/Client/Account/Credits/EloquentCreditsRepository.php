<?php namespace App\Repositories\Client\Account\Wallet;

use App\Models\Account\Credits;
use App\Models\Account\CreditsRecord;
use App\Repositories\Client\Account\EloquentAccountRepository;

class EloquentCreditsRepository extends EloquentAccountRepository implements CreditsRepositoryContract {

    protected function init()
    {
        $this->setAccountModel(Credits::class);
        $this->setAccountRecordModel(CreditsRecord::class);
    }

}
