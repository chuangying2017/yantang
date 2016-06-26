<?php namespace App\Services\Store\Statement;

use App\Events\Store\StoreStatementConfirm;
use App\Repositories\OrderTicket\OrderTicketStatementRepoContract;
use App\Repositories\Store\Statement\StoreStatementRepositoryContract;
use App\Repositories\Store\StoreProtocol;
use App\Repositories\Store\StoreRepositoryContract;
use Carbon\Carbon;

class StoreStatementService implements StoreStatementServiceContract {


    /**
     * @var StoreStatementRepositoryContract
     */
    private $statementRepo;
    /**
     * @var OrderTicketStatementRepoContract
     */
    private $ticketRepo;
    /**
     * @var StoreRepositoryContract
     */
    private $storeRepo;

    /**
     * StoreStatementService constructor.
     * @param StoreStatementRepositoryContract $statementRepo
     */
    public function __construct(StoreStatementRepositoryContract $statementRepo, OrderTicketStatementRepoContract $ticketRepo, StoreRepositoryContract $storeRepo)
    {
        $this->statementRepo = $statementRepo;
        $this->ticketRepo = $ticketRepo;
        $this->storeRepo = $storeRepo;
    }

    public function generateStatements()
    {
        $stores = $this->storeRepo->getAll();
        foreach ($stores as $store) {
            $this->generateStoreStatement($store['id']);
        }
    }

    public function generateStoreStatement($store_id)
    {
        $tickets = $this->ticketRepo->getStoreOrderTicketsWithProducts($store_id, $this->getTime());

        $settle_amount = 0;
        $product_skus_info = [];
        foreach ($tickets as $key => $ticket) {
            foreach ($ticket['skus'] as $sku) {
                $sku_key = $sku['product_sku_id'];
                $sku_total_amount = $sku['price'] * $sku['quantity'];
                if (!isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                    $product_skus_info[$sku_key]['total_amount'] += $sku_total_amount;
                } else {
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['price'] = $sku['price'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                    $product_skus_info[$sku_key]['total_amount'] = $sku_total_amount;
                }
                $settle_amount = bcadd($settle_amount, $sku_total_amount);
            }
        }

        $service_amount = $this->calSettleAmount($settle_amount, $product_skus_info);

        $statement = $this->statementRepo->createStatement($store_id, $settle_amount, $service_amount, $product_skus_info);

        $this->ticketRepo->updateOrderTicketsAsChecked(array_pluck($tickets, 'id'));

        return $statement;
    }

    public function calSettleAmount($settle_amount, $product_skus)
    {
        #todo calculate service fee & amount

        return 0;
    }

    protected function getTime()
    {
        $now = Carbon::now();
        $check_day = 1;
        return Carbon::create($now->year, $now->month, $check_day, 0, 0, 0);
    }

    public function reconciliation($store_id, $statement_no, $confirm = true, $memo = null)
    {
        $statement = $this->statementRepo->getStatement($statement_no);

        if ($statement['status'] == StoreProtocol::STATEMENT_STATUS_OF_OK) {
            throw new \Exception('已对账,无法再次操作', 403);
        }

        if ($statement['store_id'] != $store_id) {
            throw new \Exception('操作权限不足', 403);
        }

        if ($confirm) {
            $statement = $this->statementRepo->updateStatementAsOK($statement_no);
        } else {
            $statement = $this->statementRepo->updateStatementAsError($statement_no, $memo);
        }

        event(new StoreStatementConfirm($statement));

        return $statement;
    }
}
