<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 6/25/16
 * Time: 10:55 AM
 */

namespace App\Repositories\Statement;


trait QueryStatement {

    protected function queryStatements($year, $month = null, $merchant_id = null, $status = null, $per_page = null)
    {
        $model = $this->getModel();
        $query = $model::query()->where('year', $year);

        if (!is_null($month)) {
            $query->where('month', $month);
        }

        if (!is_null($merchant_id)) {
            $query->where('merchant_id', $merchant_id);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

}
