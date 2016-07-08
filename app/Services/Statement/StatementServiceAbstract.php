<?php namespace App\Services\Statement;

use App\Events\Statement\StatementConfirm;
use App\Repositories\Product\Sku\ProductSkuRepositoryContract;
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
     * @var ProductSkuRepositoryContract
     */
    private $skuRepo;

    /**
     * StoreStatementService constructor.
     * @param StatementRepositoryAbstract $statementRepo
     */
    public function __construct(
        StatementRepositoryAbstract $statementRepo,
        StatementAbleBillingRepoContract $billingRepo,
        MerchantRepositoryContract $merchantRepo,
        ProductSkuRepositoryContract $skuRepo
    )
    {
        $this->statementRepo = $statementRepo;
        $this->billingRepo = $billingRepo;
        $this->merchantRepo = $merchantRepo;
        $this->setCheckDay();
        $this->skuRepo = $skuRepo;
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

        $product_skus_info = [];
        foreach ($billings as $key => $billing) {
            foreach ($billing['skus'] as $sku) {
                $sku_key = $sku['product_sku_id'];
                if (isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                } else {
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['name'] = $sku['name'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['price'] = $sku['price'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                }
            }
        }

        $settle_amount = 0;
        $product_skus = $this->skuRepo->getSkus(array_keys($product_skus_info));
        foreach ($product_skus_info as $product_sku_info) {
            foreach ($product_skus as $product_sku) {
                if ($product_sku['id'] == $product_sku_info['product_sku_id']) {
                    $product_sku_info['total_amount'] = $product_sku_info['quantity'] * $product_sku['settle_price'];
                    $settle_amount += $product_sku_info['total_amount'];
                    continue;
                }
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
