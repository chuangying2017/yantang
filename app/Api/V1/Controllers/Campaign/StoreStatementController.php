<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Statement\StoreStatementTransformer;
use App\Repositories\Statement\StoreStatementRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreStatementController extends Controller {

    /**
     * @var StoreStatementRepository
     */
    private $statementRepo;

    /**
     * StoreStatementController constructor.
     * @param StoreStatementRepository $statementRepo
     */
    public function __construct(StoreStatementRepository $statementRepo)
    {
        $this->statementRepo = $statementRepo;
    }

    public function index(Request $request)
    {
        $year = $request->input('year') ?: Carbon::today()->year;
        $status = $request->input('status') ?: null;
        $statements = $this->statementRepo->getAllStatementsOfMerchant(access()->storeId(), $year, $status);

        return $this->response->collection($statements, new StoreStatementTransformer());
    }

    public function show(Request $request, $statement_no)
    {
        $statement = $this->statementRepo->getStatement($statement_no, true);

        return $this->response->item($statement, new StoreStatementTransformer());
    }

    public function update(Request $request, $statement_no)
    {
        $confirm = $request->input('confirm') === 0 ? 0 : 1;

        if ($confirm) {
            $statement = $this->statementRepo->updateStatementAsOK($statement_no);
        } else {
            $statement = $this->statementRepo->updateStatementAsError($statement_no, $request->input('memo') ?: '');
        }

        return $this->response->item($statement, new StoreStatementTransformer());
    }

}
