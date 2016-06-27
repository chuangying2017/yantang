<?php namespace App\Services\Statement;

use App\Repositories\OrderTicket\EloquentOrderTicketRepository;
use App\Repositories\Store\Statement\StoreStatementRepository;
use App\Repositories\Store\StoreRepository;

class StoreStatementService extends StatementServiceAbstract {

    public function __construct(StoreStatementRepository $statementRepo, EloquentOrderTicketRepository $billingRepo, StoreRepository $merchantRepo)
    {
        $this->type = StatementProtocol::TYPE_OF_STORE;
        parent::__construct($statementRepo, $billingRepo, $merchantRepo);
    }

    protected function setCheckDay()
    {
        return 15;
    }
}
