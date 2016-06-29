<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Services\Preorder\PreorderManageServiceContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;

class StationPreorderController extends Controller {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $orderRepo;

    /**
     * StationPreorderController constructor.
     * @param StationPreorderRepositoryContract $orderRepo
     */
    public function __construct(StationPreorderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $charge_status = $request->input('charge_status') ?: PreorderProtocol::CHARGE_STATUS_OF_OK;

        $orders = $this->orderRepo->getPreordersOfStation(access()->stationId(), null, $status, $charge_status, $start_time, $end_time);

        return $this->response->paginator($orders, new PreorderTransformer());
    }


    public function notConfirm()
    {
        $orders = $this->orderRepo->getPreordersOfStationNotConfirm(access()->stationId());

        return $this->response->collection($orders, new PreorderTransformer());
    }

    public function show($order_id)
    {
        $order = $this->orderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }


    public function update(Request $request, PreorderManageServiceContract $preorderManageService, $order_id)
    {
        $product_skus = $request->input('product_skus');
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;

        $order = $this->orderRepo->get($order_id);

        if ($order['status'] == PreorderProtocol::ORDER_STATUS_OF_ASSIGNING) {
            $order = $preorderManageService->init($order_id, $product_skus, $start_time, $end_time);
        } else {
            $order = $preorderManageService->change($order_id, $product_skus, $start_time, $end_time);
        }

        return $this->response->item($order, new PreorderTransformer());
    }

    public function pause(Request $request, PreorderManageServiceContract $preorderManageService, $order_id)
    {
        $stop_time = $request->input('stop_time');
        $restart_time = $request->input('restart_time') ?: null;

        $order = $preorderManageService->pause($order_id, $stop_time, $restart_time);

        return $this->response->item($order, new PreorderTransformer());
    }

    public function reject(Request $request, PreorderAssignRepositoryContract $assign, $order_id)
    {
        $memo = $request->input('memo');

        $assign->updateAssignAsReject($order_id, $memo);

        return $this->response->noContent();
    }


}
