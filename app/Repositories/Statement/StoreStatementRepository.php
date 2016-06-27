<?php namespace App\Repositories\Store\Statement;


use App\Models\Statement\StoreStatement;

class StoreStatementRepository extends StatementRepositoryAbstract {

    protected function setModel()
    {
        return $this->model = StoreStatement::class;
    }
}
