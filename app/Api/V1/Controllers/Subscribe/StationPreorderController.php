<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Services\Preorder\PreorderManageServiceContract;
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

    public function info(PreorderManageServiceContract $preorderManager, Request $request)
    {
        $day = $request->input('day');
        $daytime = $request->input('daytime');

        $daily_info = $preorderManager->stationDailyInfo(access()->stationId(), $day, $daytime);

        return $this->response->array(['data' => array_values($daily_info)]);
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;

        $orders = $this->orderRepo->getPreordersOfStation(access()->stationId(), null, $status, $start_time, $end_time);

        return $this->response->paginator($orders, new PreorderTransformer());
    }


    public function show($order_id)
    {
        $order = $this->orderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }

    public function pause(Request $request, PreorderManageServiceContract $preorderManageService, $order_id)
    {
        $stop_time = $request->input('pause_time');
        $restart_time = $request->input('restart_time') ?: null;

        $order = $preorderManageService->pause($order_id, $stop_time, $restart_time);
        return $this->response->item($order, new PreorderTransformer());
    }

    public function restart(Request $request, PreorderManageServiceContract $preorderManageService, $order_id)
    {
        $restart_time = $request->input('restart_time');

        $order = $preorderManageService->restart($order_id, $restart_time);

        return $this->response->item($order, new PreorderTransformer());
    }

    public function confirm(Request $request, PreorderAssignRepositoryContract $assign, $order_id)
    {
        $assign = $assign->updateAssignAsConfirm($order_id);

        return $this->response->array(['data' => $assign['preorder_id']]);
    }

    public function reject(Request $request, PreorderAssignRepositoryContract $assign, $order_id)
    {
        $memo = $request->input('memo') ?: '';

        $assign = $assign->updateAssignAsReject($order_id, $memo);

        return $this->response->array(['data' => $assign['preorder_id']]);
    }


}
