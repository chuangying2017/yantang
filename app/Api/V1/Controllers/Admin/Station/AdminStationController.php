<?php

namespace App\Api\V1\Controllers\Admin\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use App\Api\V1\Requests\Station\AdminStationRequest;
use Auth;
use DB;

class AdminStationController extends Controller
{
    protected $staff;
    protected $station;
    protected $station_id;
    const STATION_PER_PAGE = 10;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->staffs = $staffs;
        $this->station = $station;
        $this->station_id = 1;
    }

    /**
     * 查看服务部列表
     */
    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $paginate = $request->input('paginate', self::STATION_PER_PAGE);
        $where = [
            ['field' => 'name', 'value' => $name, 'compare_type' => '=']
        ];
        $station = $this->station->Paginated($paginate, $where);

        return $this->response->item($station, new StationTransformer());
    }

    public function store(AdminStationRequest $request)
    {
        $input = $request->only(['name', 'desc', 'user_id', 'address', 'district', 'tel', 'director', 'phone', 'cover_image', 'longitude', 'latitude', 'status']);
        $station = $this->station->create($input);
        return $this->response->item($station, new StationTransformer())->setStatusCode(201);
    }

    public function update(AdminStationRequest $request, $id)
    {
        $input = $request->only(['name', 'desc', 'user_id', 'address', 'district', 'tel', 'phone', 'cover_image', 'longitude', 'latitude']);
        $station = $this->station->update($input, $id);
        return $this->response->item($station, new StationTransformer())->setStatusCode(201);
    }

    public function show($id)
    {
        try {
            $station = $this->station->show($id);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($station, new StationTransformer());
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->station->destroy($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->noContent();
    }
}
