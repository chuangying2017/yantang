<?php namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Station\AssignStaffRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderAssignTransformer;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;

class StationAssignController extends Controller {

    /**
     * @var PreorderAssignRepositoryContract
     */
    private $assignRepo;

    /**
     * StationAssignController constructor.
     */
    public function __construct(PreorderAssignRepositoryContract $assignRepo)
    {
        $this->assignRepo = $assignRepo;
    }

    public function store(AssignStaffRequest $request, $order_id)
    {
        $staff_id = $request->input('staff');

        $assign = $this->assignRepo->updateAssignStaff($order_id, $staff_id);

        return $this->response->item($assign, new PreorderAssignTransformer())->setStatusCode(201);
    }

    public function destroy($order_id)
    {
        $this->assignRepo->deleteAssignStaff($order_id);

        return $this->response->noContent();
    }


}
