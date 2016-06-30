<?php namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffTransformer;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StationStaffController extends Controller {


    /**
     * @var StaffRepositoryContract
     */
    private $staffRepo;

    /**
     * StaffController constructor.
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
    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;

        $staffs = $this->staffRepo->getAll(access()->stationId(), $status);

        return $this->response->collection($staffs, new StaffTransformer());
    }


    /**
     * Staff a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $staff = $this->staffRepo->createStaff(access()->stationId(), $request->all());
        $staff->bind_token = $this->staffRepo->getBindToken($staff['id']);

        return $this->response->item($staff, new StaffTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->checkAuth($id);

        $staff = $this->staffRepo->getStaff($id, false);
        $staff->bind_token = $this->staffRepo->getBindToken($staff['id']);

        return $this->response->item($staff, new StaffTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->checkAuth($id);

        $staff = $this->staffRepo->updateStaff($id, $request->all());

        return $this->response->item($staff, new StaffTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->checkAuth($id);

        $this->staffRepo->deleteStaff($id);

        return $this->response->noContent();
    }

    public function orders(Request $request, $id, StationPreorderRepositoryContract $preorderRepo)
    {
        $day = $request->input('day');
        $daytime = $request->input('daytime');

        $this->checkAuth($id);

        $orders = $preorderRepo->getDayPreordersOfStaff($id, $day, $daytime);

        return $this->response->collection($orders, new StaffPreorderTransformer());
    }

    /**
     * @param $id
     */
    protected function checkAuth($id)
    {
        $staff = $this->staffRepo->getStaff($id);
        if ($staff['station_id'] !== access()->stationId()) {
            throw new AccessDeniedHttpException('无权查看配送员信息');
        }
    }

}
