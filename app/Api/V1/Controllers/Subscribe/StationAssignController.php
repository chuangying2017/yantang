<?php namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Station\AssignStaffRequest;
use App\Repositories\Station\StationPreorderRepositoryContract;

class StationAssignController extends Controller {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $stationPreorderRepo;

    /**
     * StationAssignController constructor.
     * @param StationPreorderRepositoryContract $stationPreorderRepo
     */
    public function __construct(StationPreorderRepositoryContract $stationPreorderRepo)
    {
        $this->stationPreorderRepo = $stationPreorderRepo;
    }

    public function store(AssignStaffRequest $request, $order_id)
    {
        $staff_id = $request->input('staff');

        $this->stationPreorderRepo->updatePreorderAssign($order_id, null, $staff_id);

        return $this->response->created();
    }

    public function destroy($order_id)
    {
        $this->stationPreorderRepo->updatePreorderAssign($order_id, null, null);

        return $this->response->noContent();
    }


}
