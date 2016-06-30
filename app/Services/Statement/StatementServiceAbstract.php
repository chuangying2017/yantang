<?php namespace App\Services\Statement;

use App\Events\Statement\StatementConfirm;
use App\Repositories\Statement\MerchantRepositoryContract;
use App\Repositories\Statement\StatementAbleBillingRepoContract;
use App\Repositories\Statement\StatementRepositoryAbstract;
use Carbon\Carbon;

abstract class StatementServiceAbstract {


    protected $type;

    /**
     * @var StatementRepositoryAbstract
     */
    protected $statementRepo;
    /**
     * @var StatementAbleBillingRepoContract
     */
    protected $billingRepo;
    /**
     * @var MerchantRepositoryContract
     */
    protected $merchantRepo;

    //结算日
    protected $check_day;

    /**
     * StoreStatementService constructor.
     * @param StatementRepositoryAbstract $statementRepo
     */
    public function __construct(StatementRepositoryAbstract $statementRepo, StatementAbleBillingRepoContract $billingRepo, MerchantRepositoryContract $merchantRepo)
    {
        $this->statementRepo = $statementRepo;
        $this->billingRepo = $billingRepo;
        $this->merchantRepo = $merchantRepo;
        $this->setCheckDay();
    }

    public function generateStatements()
    {
        $merchants = $this->merchantRepo->getAll();
        foreach ($merchants as $merchant) {
            $this->generateMerchantStatement($merchant['id']);
        }

    }

    public function generateMerchantStatement($merchant_id)
    {
        $billings = $this->billingRepo->getBillingWithProducts($merchant_id, $this->getTime());

        $settle_amount = 0;
        $product_skus_info = [];
        foreach ($billings as $key => $billing) {
            foreach ($billing['skus'] as $sku) {
                $sku_key = $sku['product_sku_id'];
                $sku_total_amount = $sku['price'] * $sku['quantity'];
                if (isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                    $product_skus_info[$sku_key]['total_amount'] += $sku_total_amount;
                } else {
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['price'] = $sku['price'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                    $product_skus_info[$sku_key]['total_amount'] = $sku_total_amount;
                }
                $settle_amount = $settle_amount + $sku_total_amount;
            }
        }


        $service_amount = $this->calServiceAmount($settle_amount, $product_skus_info);

        $statement = $this->statementRepo->createStatement($merchant_id, $settle_amount, $service_amount, $product_skus_info);

        $this->billingRepo->updateBillingAsCheckout(array_pluck($billings, 'id'), $statement['statement_no']);

        return $statement;
    }

    public function calServiceAmount($settle_amount, $product_skus)
    {
        #todo calculate service fee & amount

        return 0;
    }

    protected function getTime()
    {
        $now = Carbon::now();
        return Carbon::create($now->year, $now->month, $this->check_day, 0, 0, 0);
    }

    public function reconciliation($merchant_id, $statement_no, $confirm = true, $memo = null)
    {
        $statement = $this->statementRepo->getStatement($statement_no);

        $this->validStatement($merchant_id, $statement);

        if ($confirm) {
            $statement = $this->statementRepo->updateStatementAsOK($statement_no);
        } else {
            $statement = $this->statementRepo->updateStatementAsError($statement_no, $memo);
        }

        event(new StatementConfirm($statement));

        return $statement;
    }

    /**
     * @param $merchant_id
     * @param $statement
     * @throws \Exception
     */
    protected function validStatement($merchant_id, $statement)
    {
        if ($statement['status'] == StatementProtocol::STATEMENT_STATUS_OF_OK) {
            throw new \Exception('已对账,无法再次操作', 403);
        }

        if (!(($statement['merchant_id'] == $merchant_id) && $statement['type'] == $this->type)) {
            throw new \Exception('操作权限不足', 403);
        }
    }

    protected abstract function setCheckDay();
}
