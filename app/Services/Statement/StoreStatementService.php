<?php namespace App\Services\Statement;

use App\Repositories\OrderTicket\EloquentOrderTicketRepository;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Statement\StoreStatementRepository;
use App\Repositories\Store\StoreRepository;

class StoreStatementService extends StatementServiceAbstract {

    public function __construct(StoreStatementRepository $statementRepo, EloquentOrderTicketRepository $billingRepo, StoreRepository $merchantRepo, ProductSkuRepositoryContract $skuRepo)
    {
        $this->type = StatementProtocol::TYPE_OF_STORE;
        parent::__construct($statementRepo, $billingRepo, $merchantRepo, $skuRepo);
    }

    protected function setCheckDay()
    {
        return $this->check_day = StatementProtocol::getStoreCheckDay();
    }
}
