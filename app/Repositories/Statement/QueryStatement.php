<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 6/25/16
 * Time: 10:55 AM
 */

namespace App\Repositories\Store\Statement;


use App\Models\Store\Statement;

trait QueryStatement {

    protected function queryStatements($year, $month = null, $store_id = null, $status = null, $per_page = null)
    {
        $query = Statement::query()->where('year', $year);

        if (!is_null($month)) {
            $query->where('month', $month);
        }

        if (!is_null($store_id)) {
            $query->where('store_id', $store_id);
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
