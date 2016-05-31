<?php

namespace App\Api\V1\Controllers\Subscribe\Station;


use App\Api\V1\Controllers\Controller;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use Auth;
use App\Api\V1\Transformers\Subscribe\Station\StationPreorderTransformer;
use App\Repositories\Subscribe\StaffPreorder\StaffPreorderRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;

class StationPreorderController extends Controller
{
    protected $staff;
    protected $station;
    protected $user_id;
    const PER_PAGE = 10;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->station = $station;
        //        $this->user_id = Auth::user()->id();
        $this->user_id = 3;
    }

    /**
     * 查看服务部下订单列表
     */
    public function index(Request $request)
    {
        $user_id = $this->user_id;
        $type = $request->input('type', null);

        $station = $this->station->preorder($user_id, $type, self::PER_PAGE);

        return $this->response->item($station, new StationPreorderTransformer());
    }

    public function store(Request $request, StaffPreorderRepositoryContract $staffPreorder)
    {
        $input = $request->only(['preorder_id', 'preorder_id', 'index']);
        if (empty($input['index'])) {
            $input['index'] = 0;
        }
        try {
            $station = $this->station->getByUserId($this->user_id);
            if (!$station) {
                throw new \Exception("该会员不属于服务部");
            }
            $input['station_id'] = $station->id;
            $staffPreorder = $staffPreorder->create($input);
            return $this->response->item($staffPreorder, new StaffPreorderTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }
}
