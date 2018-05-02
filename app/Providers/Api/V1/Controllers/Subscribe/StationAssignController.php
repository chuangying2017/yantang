<?php namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Station\AssignStaffRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderAssignTransformer;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StationAssignController extends Controller {

    /**
     * @var PreorderAssignRepositoryContract
     */
    private $assignRepo;

    /**
     * StationAssignController constructor.
     * @param PreorderAssignRepositoryContract $assignRepo
     * @param PreorderRepositoryContract $orderRepo
     */
    public function __construct(PreorderAssignRepositoryContract $assignRepo, PreorderRepositoryContract $orderRepo)
    {
        $this->assignRepo = $assignRepo;
        $this->orderRepo = $orderRepo;
    }

    public function store(AssignStaffRequest $request, $order_id)
    {
        $order = $this->orderRepo->get($order_id, false);
        if ($order['station_id'] != access()->stationId()) {
            throw new AccessDeniedHttpException();
        }

        $staff_id = $request->input('staff');

        $assign = $this->assignRepo->updateAssignStaff($order_id, $staff_id);

        return $this->response->item($assign, new PreorderAssignTransformer())->setStatusCode(201);
    }

    public function destroy($order_id)
    {
        $this->assignRepo->deleteAssignStaff($order_id);

        return $this->response->noContent();
    }

    /**
     * @var PreorderRepositoryContract
     */
    private $orderRepo;


}
