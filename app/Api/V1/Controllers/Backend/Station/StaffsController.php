<?php

namespace App\Api\V1\Controllers\Backend\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Backend\Station\StaffsTransformer;
use App\Repositories\Backend\Staff\StaffRepositoryContract;
use Illuminate\Http\Request;

class StaffsController extends Controller
{
    protected $staff;

    public function __construct(StaffRepositoryContract $staffs)
    {
        $this->staffs = $staffs;
    }

    /**
     * 员工信息列表
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $order_by = $request->input('order_by', 'id');
        $sort = $request->input('sort', 'asc');
        $staffs = $this->staffs->getStaffPaginated($per_page, $order_by, $sort);
        return $this->response->paginator($staffs, new StaffsTransformer());
    }

    public function show($id)
    {
        try {
            $staffs = $this->staffs->show($id);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($staffs, new StaffsTransformer());
    }
}
