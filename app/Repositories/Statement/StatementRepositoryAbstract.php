<?php namespace App\Repositories\Statement;

use App\Models\Statement\StatementAbstract;
use App\Models\Statement\StatementProduct;
use App\Models\Statement\StationStatement;
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

    public function getAllStatements($year, $month = null, $status = null)
    {
        return $this->queryStatements($year, $month, null, $status);
    }

    public function getAllStatementsOfMerchant($merchant_id, $year, $status = null)
    {
        return $this->queryStatements($year, null, $merchant_id, $status);
    }

    public function createStatement($merchant_id, $settle_amount, $service_amount, $product_skus_info)
    {
        $state = $this->getStatementByTime(Carbon::today()->year, Carbon::today()->month, $merchant_id);

        if ($state) {
            return $state;
        }

        \DB::beginTransaction();

        $statement_model = $this->getModel();

        $statement = $statement_model::create([
            'statement_no' => NoGenerator::generateStoreStatementNo($merchant_id, $this->getModel()),
            'year' => Carbon::today()->year,
            'month' => Carbon::today()->month,
            'merchant_id' => $merchant_id,
            'settle_amount' => $settle_amount,
            'service_amount' => $service_amount,
            'status' => StatementProtocol::STATEMENT_STATUS_OF_PENDING,
        ]);

        $this->setStatementDetail($statement, $product_skus_info);

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
            $statement = $statement_model::query()->findOrFail($statement_no);
        }

        if ($with_detail) {
            $statement->load('products');
        }

        return $statement;
    }

    public function getStatementByTime($year, $month, $merchant_id)
    {
        $model = $this->getModel();
        return $model::query()
            ->where('year', $year)
            ->where('month', $month)
            ->where('merchant_id', $merchant_id)
            ->first();
    }

    public function getStatementDetail($statement_no)
    {
        return StatementProduct::query()->where('statement_no', $statement_no)->get();
    }

    public function setStatementDetail($statement, $product_skus_info)
    {
        foreach ($product_skus_info as $product_sku) {
            StatementProduct::create([
                'statement_no' => $statement['statement_no'],
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
