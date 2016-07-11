<?php namespace App\Services\Statement;

use App\Repositories\Billing\PreorderBillingRepository;
use App\Repositories\Preorder\Deliver\PreorderDeliverRepository;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
use App\Repositories\Station\EloquentStationRepository;
use App\Repositories\Statement\StationStatementRepository;

class StationStatementService extends StatementServiceAbstract {


    public function __construct(StationStatementRepository $statementRepo, PreorderDeliverRepository $billingRepo, EloquentStationRepository $merchantRepo, ProductSkuRepositoryContract $skuRepo)
    {
        $this->type = StatementProtocol::TYPE_OF_STORE;
        parent::__construct($statementRepo, $billingRepo, $merchantRepo, $skuRepo);
    }

    protected function setCheckDay()
    {
        return StatementProtocol::getStationCheckDay();
    }
}
