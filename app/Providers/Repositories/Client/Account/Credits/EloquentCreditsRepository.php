<?php namespace App\Repositories\Client\Account\Credits;

use App\Models\Client\Account\Credits;
use App\Models\Client\Account\CreditsRecord;
use App\Repositories\Client\Account\EloquentAccountRepository;

class EloquentCreditsRepository extends EloquentAccountRepository implements CreditsRepositoryContract {

    protected function init()
    {
        $this->setAccountModel(Credits::class);
        $this->setAccountRecordModel(CreditsRecord::class);
    }

}
