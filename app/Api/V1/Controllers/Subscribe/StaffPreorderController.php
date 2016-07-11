<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;
use App\Repositories\Station\StationPreorderRepositoryContract;
use App\Services\Preorder\PreorderManageServiceContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class StaffPreorderController extends Controller {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $orderRepo;

    /**
     * StaffPreorderController constructor.
     * @param StationPreorderRepositoryContract $orderRepo
     */
    public function __construct(StationPreorderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function daily(Request $request)
    {
        $date = $request->input('day');
        $daytime = $request->input('daytime');

        $orders = $this->orderRepo->getDayPreorderWithProductsOfStaff(access()->staffId(), $date, $daytime);

        return $this->response->collection($orders, new PreorderTransformer())->setMeta(['summery' => $this->transformOrder($orders)]);
    }

    public function index(Request $request)
    {
        $date = $request->input('day') ?: null;
        $daytime = $request->input('daytime');
        $status = $request->input('status') ?: null;

        $orders = $this->orderRepo->getPreordersOfStaff(access()->staffId(), $status, $date, $daytime);

        return $this->response->collection($orders, new PreorderTransformer());
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
