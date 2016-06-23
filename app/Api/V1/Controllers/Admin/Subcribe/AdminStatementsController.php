<?php namespace App\Api\V1\Controllers\Admin\Subcribe;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;
use StatementsService;
use App\Repositories\Subscribe\Statements\StatementsRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Station\StatementsTransformer;

class AdminStatementsController extends Controller
{
    protected $statementsRepo;
    const PER_PAGE = 20;

    public function __construct(StatementsRepositoryContract $statementsRepo)
    {
        $this->statementsRepo = $statementsRepo;
    }

    public function index(Request $request)
    {
        $per_page = $request->input('paginate', self::PER_PAGE);
        $statements = $this->statementsRepo->info($per_page);
        return $this->response->paginator($statements, new StatementsTransformer());
    }

    public function createBilling(Request $request)
    {
        $input = $request->only(['begin_time', 'end_time']);
        StatementsService::create($input);
        return $this->response->noContent();
    }

    public function show($statements_id)
    {
        $statements = $this->statementsRepo->show($statements_id);
        if ($statements) {
            $statements->detail = true;
        }
        return $this->response->item($statements, new StatementsTransformer());
    }

    public function update(Request $request, $statements_id)
    {
        $input = $request->only(['station_id', 'settle_amount', 'service_amount']);
        $statements = $this->statementsRepo->update($input, $statements_id);
        return $this->response->item($statements, new StatementsTransformer());
    }

}