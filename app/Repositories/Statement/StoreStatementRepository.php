<?php namespace App\Repositories\Store\Statement;

use App\Models\Store\Statement;
use App\Models\Store\StatementProduct;
use App\Repositories\NoGenerator;
use App\Repositories\Store\StoreProtocol;
use Carbon\Carbon;

class StoreStatementRepository implements StoreStatementRepositoryContract {

    use QueryStatement;

    public function getAllStatements($year, $month, $status = null)
    {
        return $this->queryStatements($year, $month, null, $status);
    }

    public function getAllStatementsOfStore($store_id, $year, $status = null)
    {
        return $this->queryStatements($year, null, $store_id, $status);
    }


    public function createStatement($store_id, $settle_amount, $service_amount, $product_skus_info)
    {
        $now = Carbon::now();
        $state = $this->getStatementByTime($now->year, $now->month, $store_id);

        if ($state) {
            return $state;
        }

        \DB::beginTransaction();


        $statement = Statement::create([
            'statement_no' => NoGenerator::generateStoreStatementNo($store_id),
            'year' => $now->year,
            'month' => $now->month,
            'store_id' => $store_id,
            'settle_amount' => $settle_amount,
            'service_amount' => $service_amount,
            'status' => StoreProtocol::STATEMENT_STATUS_OF_PENDING,
        ]);

        $this->setStatementDetail($statement['statement_no'], $product_skus_info);

        \DB::commit();

        return $statement;
    }

    public function updateStatementAsOK($statement_no)
    {
        $statement = $this->getStatement($statement_no);
        $statement['status'] = StoreProtocol::STATEMENT_STATUS_OF_OK;
        $statement['confirm_at'] = Carbon::now();

        $statement->save();

        return $statement;
    }

    public function updateStatementAsError($statement_no, $memo = '')
    {
        $statement = $this->getStatement($statement_no);
        $statement['status'] = StoreProtocol::STATEMENT_STATUS_OF_ERROR;
        $statement['memo'] = $memo;

        $statement->save();

        return $statement;
    }

    public function getStatement($statement_no, $with_detail = false)
    {
        if ($statement_no instanceof Statement) {
            $statement = $statement_no;
        } else {
            $statement = Statement::query()->findOrFail($statement_no);
        }

        if ($with_detail) {
            $statement->load('products');
        }

        return $statement;
    }

    public function getStatementByTime($year, $month, $store_id)
    {
        return Statement::query()
            ->where('year', $year)
            ->where('month', $month)
            ->where('store_id', $store_id)
            ->first();
    }

    public function getStatementDetail($statement_no)
    {
        return StatementProduct::where('statement_no', $statement_no)->get();
    }

    public function setStatementDetail($statement, $product_skus_info)
    {
        foreach ($product_skus_info as $product_sku) {
            Statement::create([
                'statement_no' => $statement['statement_no'],
                'store_id' => $statement['store_id'],
                'product_id' => $product_sku['product_id'],
                'product_sku_id' => $product_sku['product_sku_id'],
                'price' => $product_sku['price'],
                'quantity' => $product_sku['quantity'],
                'total_amount' => $product_sku['total_amount'],
                'service_fee' => array_get($product_sku, 'service_fee', 0),
            ]);
        }
    }


}
