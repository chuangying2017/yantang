<?php namespace App\Repositories\Store\Statement;

use App\Models\Statement\StatementAbstract;
use App\Repositories\Statement\StatementRepositoryContract;
use App\Repositories\NoGenerator;
use App\Services\Statement\StatementProtocol;
use Carbon\Carbon;

abstract class StatementRepositoryAbstract implements StatementRepositoryContract {

    use QueryStatement;

    /**
     * @var StatementAbstract
     */
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    public function getAllStatements($year, $month, $status = null)
    {
        return $this->queryStatements($year, $month, null, $status);
    }

    public function getAllStatementsOfMerchant($merchant_id, $year, $status = null)
    {
        return $this->queryStatements($year, null, $merchant_id, $status);
    }

    public function createStatement($merchant_id, $settle_amount, $service_amount, $product_skus_info)
    {
        $now = Carbon::now();
        $state = $this->getStatementByTime($now->year, $now->month, $merchant_id);

        if ($state) {
            return $state;
        }

        \DB::beginTransaction();

        $statement_model = $this->getModel();

        $statement = $statement_model::create([
            'statement_no' => NoGenerator::generateStoreStatementNo($merchant_id),
            'year' => $now->year,
            'month' => $now->month,
            'merchant_id' => $merchant_id,
            'settle_amount' => $settle_amount,
            'service_amount' => $service_amount,
            'status' => StatementProtocol::STATEMENT_STATUS_OF_PENDING,
        ]);

        $this->setStatementDetail($statement['statement_no'], $product_skus_info);

        \DB::commit();

        return $statement;
    }

    public function updateStatementAsOK($statement_no)
    {
        $statement = $this->getStatement($statement_no);
        $statement['status'] = StatementProtocol::STATEMENT_STATUS_OF_OK;
        $statement['confirm_at'] = Carbon::now();

        $statement->save();

        return $statement;
    }

    public function updateStatementAsError($statement_no, $memo = '')
    {
        $statement = $this->getStatement($statement_no);
        $statement['status'] = StatementProtocol::STATEMENT_STATUS_OF_ERROR;
        $statement['memo'] = $memo;

        $statement->save();

        return $statement;
    }

    public function getStatement($statement_no, $with_detail = false)
    {
        $statement_model = $this->getModel();
        if ($statement_no instanceof $statement_model) {
            $statement = $statement_no;
        } else {
            $statement = $this->model->query()->findOrFail($statement_no);
        }

        if ($with_detail) {
            $statement->load('products');
        }

        return $statement;
    }

    public function getStatementByTime($year, $month, $merchant_id)
    {
        return $this->model->query()
            ->where('year', $year)
            ->where('month', $month)
            ->where('merchant_id', $merchant_id)
            ->first();
    }

    public function getStatementDetail($statement_no)
    {
        $statement_model = $this->getModel();
        return $statement_model::where('statement_no', $statement_no)->get();
    }

    public function setStatementDetail($statement, $product_skus_info)
    {
        foreach ($product_skus_info as $product_sku) {
            $statement_model = $this->getModel();
            $statement_model::create([
                'statement_no' => $statement['statement_no'],
                'merchant_id' => $statement['merchant_id'],
                'product_id' => $product_sku['product_id'],
                'product_sku_id' => $product_sku['product_sku_id'],
                'price' => $product_sku['price'],
                'quantity' => $product_sku['quantity'],
                'total_amount' => $product_sku['total_amount'],
                'service_fee' => array_get($product_sku, 'service_fee', 0),
            ]);
        }
    }

    /**
     * @return mixed
     */
    protected function getModel()
    {
        return $this->model;
    }

    protected abstract function setModel();

}
