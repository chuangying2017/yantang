<?php

namespace App\Api\V1\Controllers\Subscribe\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StaffsTransformer;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use App\Api\V1\Requests\Subscribe\StationRequest;
use Auth;

class StaffsController extends Controller
{
    protected $staff;
    protected $station_id;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->staffs = $staffs;
        $station = $station->getByUserId(access()->id());
        $this->station_id = $station['id'];
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

    public function bindStaff(Request $request)
    {
        $staff_id = $request->input('staff_id', null);
        $user_id = access()->id();

        $staffs = $this->staffs->show($staff_id);
        if (!empty($staffs->user_id)) {
            if ($staffs->user_id == $user_id) {
                $this->response->errorInternal('该配送员已经绑定,无须重新绑定');
            } else {
                $this->response->errorInternal('该配送员已经绑定其他人,绑定不成功');
            }
        }

        $staffs = $this->staffs->bindStaff($staff_id, $user_id);

        return $this->response->item($staffs, new StaffsTransformer());
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->staffs->destroy($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->noContent();
    }


}
