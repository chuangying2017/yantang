<?php namespace App\Services\Statement;
use App\Repositories\Billing\PreorderBillingRepository;
use App\Repositories\Station\EloquentStationRepository;
use App\Repositories\Store\Statement\StationStatementRepository;

class StationStatementService extends  StatementServiceAbstract{

    public function __construct(StationStatementRepository $statementRepo, PreorderBillingRepository $billingRepo, EloquentStationRepository $merchantRepo)
    {
        $this->type = StatementProtocol::TYPE_OF_STORE;
        parent::__construct($statementRepo, $billingRepo, $merchantRepo);
    }

    protected function setCheckDay()
    {
        return 15;
    }
}
