<?php namespace App\Api\V1\Controllers\Admin\Order;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Admin\Preorders\UpdateAssignRequest;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Chart\ExcelService;
use Illuminate\Http\Request;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Services\Preorder\PreorderProtocol;
use StaffService;

class PreorderController extends Controller {

    protected $preorderRepo;
    const PER_PAGE = 20;

    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    public function index(Request $request)
    {
        $order_no = $request->input('order_no') ?: null;
        $pay_order_no = $request->input('pay_order_no') ?: null;
        $phone = $request->input('phone') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $status = $request->input('status') ?: null;
        $station_id = $request->input('station_id') ? explode(',', $request->input('station_id')) : null;

        if ($request->input('export') == 'all') {
            $orders = $this->preorderRepo->getAll($station_id, $order_no, $pay_order_no, $phone, $status, $start_time, $end_time);
            return ExcelService::downPreorder($orders);
        }

        $orders = $this->preorderRepo->getAllPaginated($station_id, $order_no, $pay_order_no, $phone, $status, $start_time, $end_time);
        $orders->load('assign', 'station');

        return $this->response->paginator($orders, new PreorderTransformer());
    }

    public function show($order_id)
    {
        $order = $this->preorderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }

    public function update(UpdateAssignRequest $request, $order_id, PreorderAssignRepositoryContract $assignRepo)
    {
        $station_id = $request->input('station');

        $assign = $assignRepo->createAssign($order_id, $station_id);

        return $this->response->array(['data' => $assign]);
    }
//
//    public function reject(Request $request)
//    {
//        $station_id = $request->input('station') ?: null;
//
//        $preorders = $this->preorderRepo->getAllReject($station_id, PreorderProtocol::PREORDER_PER_PAGE);
//
//        return $this->response->paginator($preorders, new PreorderTransformer());
//    }
//
//    public function overtime(Request $request)
//    {
//        $station_id = $request->input('station') ?: null;
//
//        $preorders = $this->preorderRepo->getAllNotAssignOnTime($station_id, PreorderProtocol::PREORDER_PER_PAGE);
//
//        return $this->response->paginator($preorders, new PreorderTransformer());
//    }

}
