<?php

namespace App\Api\V1\Controllers\Subscribe\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StaffsTransformer;
use App\Repositories\Backend\Staff\StaffRepositoryContract;
use App\Repositories\Backend\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use App\Api\V1\Requests\Station\StationRequest;

class StaffsController extends Controller
{
    protected $staff;
    protected $station_id;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->staffs = $staffs;
        //todo 需修改为方法获取的
        $this->station_id = 1;
    }

    /**
     * 员工信息列表
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $order_by = $request->input('order_by', 'id');
        $sort = $request->input('sort', 'asc');

        $station_id = $this->station_id;

        $staffs = $this->staffs->getStaffPaginated($station_id, $per_page, $order_by, $sort);

        return $this->response->paginator($staffs, new StaffsTransformer());
    }

    public function store(StationRequest $request)
    {
        $input = $request->only(['name', 'phone']);
        $input['station_id'] = $this->station_id;
        $staffs = $this->staffs->create($input);
        return $this->response->item($staffs, new StaffsTransformer())->setStatusCode(201);

    }

    public function update(StationRequest $request, $id)
    {
        $input = $request->only(['name', 'phone']);
        $station_id = $this->station_id;
        $staffs = $this->staffs->update($id, $input, $station_id);
        return $this->response->item($staffs, new StaffsTransformer())->setStatusCode(201);
    }

    public function show($id)
    {
        try {
            $staffs = $this->staffs->show($id);
            if ($staffs->station_id != $this->station_id) {
                throw new \Exception("该配送员不属于当前服务部");
            }
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($staffs, new StaffsTransformer());
    }
}
