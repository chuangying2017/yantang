<?php namespace App\Api\V1\Controllers\Subscribe\Station;

use App\Api\V1\Controllers\Controller;
use App\Repositories\Subscribe\Statements\StatementsRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Api\V1\Transformers\Subscribe\Station\StatementsTransformer;
use App\Services\Subscribe\SubscribeProtocol;

class StatementsController extends Controller
{
    protected $statementsRepo;
    protected $station;

    public function __construct(StatementsRepositoryContract $statementsRepo, StationRepositoryContract $station)
    {
        $this->statementsRepo = $statementsRepo;
        $this->station = $station;
    }

    public function index(Request $request)
    {
        $station = $this->station->getByUserId(access()->id());
        if (!$station) {
            throw new \Exception("该会员不属于服务部");
        }
        $station_id = $station->id;
        $dt = Carbon::now();
        $year = $request->input('year', $dt->year);
        $month = $request->input('month', null);
        $statements = $this->statementsRepo->byStationId($station_id, $year, $month);
        if (!is_null($month)) {
            $statements->detail = true;
        }
        return $this->response->item($statements, new StatementsTransformer());
    }

    public function accountCheck(Request $request)
    {
        $input = $request->only('status', 'statements_id', null);
        if (empty($input['status'])) {
            $this->response->errorInternal('参数错误');
        } elseif ($input['status'] != SubscribeProtocol::STATEMENTS_STATUS_OF_OK && $input['status'] != SubscribeProtocol::STATEMENTS_STATUS_OF_ERROR) {
            $this->response->errorInternal('状态值错误');
        }
        $this->statementsRepo->update($input['status'], $input['statements_id']);
        return $this->response->noContent();
    }

}