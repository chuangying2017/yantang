<?php

namespace App\Api\V1\Controllers\Admin\Statement;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Statement\StoreStatementTransformer;
use App\Repositories\Statement\StoreStatementRepository;
use App\Services\Statement\StatementProtocol;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        $month = $request->input('month') ?: Carbon::today()->month;
        $per_page = $request->input('per_page') ?: StatementProtocol::PER_PAGE;
        $statements = $this->statementRepo->getAllStatements($year, $month, $status, $per_page);

        $statements->load('store');

        return $this->response->paginator($statements, new StoreStatementTransformer());
    }

    public function show(Request $request, $statement_no)
    {
        $statement = $this->statementRepo->getStatement($statement_no, true);

        $statement->load('store');

        return $this->response->item($statement, new StoreStatementTransformer());
    }

}
