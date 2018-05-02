<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Station\BindStaffRequest;
use App\Api\V1\Transformers\Subscribe\Station\StaffTransformer;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class StaffController extends Controller {

    /**
     * @var StaffRepositoryContract
     */
    private $staffRepo;

    /**
     * StationController constructor.
     * @param StaffRepositoryContract $staffRepo
     */
    public function __construct(StaffRepositoryContract $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        try {
            $staff = $this->staffRepo->getStaffByUser(access()->id(), true);

            return $this->response->item($staff, new StaffTransformer());

        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 403);
        }
    }

    /**
     * Staff a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBind(BindStaffRequest $request, $staff_id)
    {
        $staff = $this->staffRepo->getStaff($staff_id);
        $staff['bind_token'] = $this->staffRepo->getBindToken($staff_id);

        return $this->response->item($staff, new StaffTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postBind(BindStaffRequest $request, $staff_id)
    {
        $success = $this->staffRepo->bindUser($staff_id, access()->id());

        if ($success) {
            return $this->response->created();
        }

        $this->response->errorBadRequest('绑定失败');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postUnBind(Request $request, $staff_id)
    {
        $success = $this->staffRepo->unbindUser($staff_id, access()->id());

        if (!$success) {
            $this->response->errorForbidden('解绑失败');
        }

        return $this->response->noContent();
    }


    public function index(Request $request)
    {
        $station_id = $request->input('station_id');
        $all = $request->input('all') ?: 0;
        if ($all) {
            $staffs = $this->staffRepo->getAll(null, null, true);
        } else {
            $staffs = $this->staffRepo->getAllActive($station_id);
        }
        $staffs->load('station');

        return $this->response->collection($staffs, new StaffTransformer());
    }

}
