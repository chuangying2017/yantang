<?php namespace App\Repositories\Preorder;

use App\Models\Subscribe\Preorder;
use App\Repositories\NoGenerator;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationProtocol;
use Carbon\Carbon;
use App\Services\Subscribe\PreorderProtocol;

class EloquentPreorderRepository implements PreorderRepositoryContract {

    public function createPreorder($data)
    {
        $order = Preorder::create([
            'user_id' => $data['user_id'],
            'order_no' => NoGenerator::generatePreorderNo(),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'district_id' => $data['district_id'],
            'address' => $data['address'],
            'station_id' => $data['station_id'],
            'status' => PreorderProtocol::ORDER_STATUS_OF_READY,
            'charge_status' => PreorderProtocol::CHARGE_STATUS_OF_NULL,
        ]);

        $order->assign = app()->make(PreorderAssignRepositoryContract::class)->createAssign($order['id'], $data['station_id']);

        return $order;
    }

    protected function createSkus($order, $product_skus)
    {
        return app()->make(PreorderSkusRepositoryContract::class)->createPreorderProducts($order['id'], $product_skus);
    }


    public function updatePreorder($preorder_id, $start_time = null, $end_time = null, $product_skus = null)
    {
        $order = $this->get($preorder_id);
    }

    public function getPaginatedByUser($user_id, $status)
    {
        // TODO: Implement getPaginatedByUser() method.
    }

    public function getPaginatedByStation($station_id, $status, $per_page = StationProtocol::STATION_PER_PAGE)
    {
        // TODO: Implement getPaginatedByStation() method.
    }

    public function getPaginatedByStaff($staff_id, $status, $per_page = StationProtocol::STATION_PER_PAGE)
    {

    }

    protected function queryOrders($owner, $status = null, $start_time = null, $end_time = null, $per_page = null, $orderBy = 'created_at', $sort = 'desc')
    {
        $query = Preorder::query()->where($owner);


    }

    public function get($preorder_id, $with_detail = false)
    {
        if ($preorder_id instanceof $preorder_id) {
            $order = $preorder_id;
        } else if (strlen($preorder_id) == NoGenerator::LENGTH_OF_PREORDER_NO) {
            $order = Preorder::query()->where('order_no', $preorder_id)->firstOrFail();
        } else {
            $order = Preorder::query()->findOrFail($preorder_id);
        }

        if ($with_detail) {
            $order->load('skus', 'billings', 'station', 'staff', 'user');
        }

        return $order;
    }

    public function deletePreorder($preorder_id)
    {
        return Preorder::destroy($preorder_id);
    }
}
