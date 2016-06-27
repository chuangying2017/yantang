<?php namespace App\Repositories\Store\Statement;

use App\Models\Statement\StationStatement;

class StationStatementRepository extends StatementRepositoryAbstract {

    protected function setModel()
    {
        $this->model = StationStatement::class;
    }
}
