<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderDeliverTransformer;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\Deliver\PreorderDeliverRepository;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Services\Preorder\PreorderManageServiceContract;
use Carbon\Carbon;
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

    public function daily(Request $request)
    {
        $day = $request->input('date');
        $daytime = $request->input('daytime');

        $orders = $this->orderRepo->getDayPreorderWithProductsByStation(access()->stationId(), $day, $daytime);

        return $this->response->array(['data' => array_values($this->transformOrder($orders))]);
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

    public function deliver(Request $request, PreorderDeliverRepository $preorderDeliverRepo)
    {
        $date = $request->input('date') ?: Carbon::today();

        $delivers = $preorderDeliverRepo->getAll(access()->stationId(), $date);

        return $this->response->collection($delivers, new PreorderDeliverTransformer());
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


    protected function transformOrder($orders)
    {
        $product_skus_info = [];
        foreach ($orders as $key => $order) {
            if (!count($order['skus'])) {
                continue;
            }
            foreach ($order['skus'] as $sku) {
                $sku_key = $sku['product_sku_id'];
                if (isset($product_skus_info[$sku_key])) {
                    $product_skus_info[$sku_key]['quantity'] += $sku['quantity'];
                } else {
                    $product_skus_info[$sku_key]['product_id'] = $sku['product_id'];
                    $product_skus_info[$sku_key]['product_sku_id'] = $sku['product_sku_id'];
                    $product_skus_info[$sku_key]['quantity'] = $sku['quantity'];
                    $product_skus_info[$sku_key]['name'] = $sku['name'];
                    $product_skus_info[$sku_key]['cover_image'] = $sku['cover_image'];
                }
            }
        }

        return $product_skus_info;
    }


}
