<?php

namespace App\Api\V1\Controllers\Subscribe\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Api\V1\Requests\Station\StationRequest;
use Auth;
use StationService;

class StationController extends Controller
{
    protected $staff;
    protected $station;
    protected $station_id;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->station = $station;
    }

    /**
     * 查看服务部信息
     */
    public function index()
    {
        $user_id = access()->id();

        $station = $this->station->getByUserId($user_id);

        return $this->response->item($station, new StationTransformer());
    }

    public function bindStation(Request $request)
    {
        $user_id = access()->id();
        $station_id = $request->input('station_id', null);
        $station = $this->station->show($station_id);
        if (!empty($station->user_id)) {
            if ($station->user_id == $user_id) {
                $this->response->errorInternal('该服务部已经绑定,无须重新绑定');
            } else {
                $this->response->errorInternal('该服务部已经绑定其他人,绑定不成功');
            }
        }
        try {
            $station = $this->station->bindStation($station_id, $user_id);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($station, new StationTransformer());
    }

    public function claimGoods(Request $request)
    {
        $query_day = $request->input('date', Carbon::now());
//        $query_day = '2016-06-06';
        $data = StationService::claimGoods($query_day);
        return $this->response->array($data);
    }

}
