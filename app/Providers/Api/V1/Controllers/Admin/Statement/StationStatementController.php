<?php

namespace App\Api\V1\Controllers\Admin\Statement;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Statement\StationStatementTransformer;
use App\Repositories\Statement\StationStatementRepository;
use App\Services\Statement\StatementProtocol;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class StationStatementController extends Controller {


    /**
     * @var StationStatementRepository
     */
    private $statementRepo;

    /**
     * StatementController constructor.
     * @param StationStatementRepository $statementRepo
     */
    public function __construct(StationStatementRepository $statementRepo)
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

        $statements->load('station');

        return $this->response->paginator($statements, new StationStatementTransformer());
    }

    public function show(Request $request, $statement_no)
    {
        $statement = $this->statementRepo->getStatement($statement_no, true);

        $statement->load('station');
        
        return $this->response->item($statement, new StationStatementTransformer());
    }

}
