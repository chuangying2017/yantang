<?php namespace App\Repositories\Statement;

use App\Models\Statement\StationStatement;

class StationStatementRepository extends StatementRepositoryAbstract {

    protected function setModel()
    {
        $this->model = StationStatement::class;
    }


}
