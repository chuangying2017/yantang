<?php namespace App\Repositories\Preorder;

use App\Models\Subscribe\Preorder;
use App\Repositories\NoGenerator;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Carbon\Carbon;
use App\Services\Subscribe\PreorderProtocol;

class EloquentPreorderRepository implements PreorderRepositoryContract, StationPreorderRepositoryContract {

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

    public function updatePreorder($preorder_id, $start_time = null, $end_time = null, $product_skus = null, $station_id = null)
    {
        $order = $this->get($preorder_id);



    }

    public function getPaginatedByUser($user_id, $status)
    {
        return $this->queryOrders(['user_id' => $user_id], $status);
    }

    protected function queryOrders($owner, $status = null, $charge_status = null, $start_time = null, $end_time = null, $per_page = null, $orderBy = 'created_at', $sort = 'desc')
    {
        $query = Preorder::query()->where($owner);

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($charge_status)) {
            $query->where('charge_status', $charge_status);
        }

        if (!is_null($start_time)) {
            $query->where('end_time', '>=', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('start_time', '<=', $end_time);
        }

        $query->orderBy($orderBy, $sort);

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
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

    public function getPreordersOfStation($station_id, $staff_id = null, $status = null, $charge_status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        $owner = is_null($staff_id) ? compact('station_id') : compact('station_id', 'staff_id');
        return $this->queryOrders($owner, $status, $charge_status, $start_time, $end_time, $per_page, $order_by, $sort);
    }

    public function getTodayPreordersOfStation($station_id, $staff_id = null, $daytime = null, $per_page = null)
    {
        $orders = $this->getPreordersOfStation($station_id, $staff_id, PreorderProtocol::ORDER_STATUS_OF_PENDING, PreorderProtocol::CHARGE_STATUS_OF_OK, Carbon::today(), Carbon::today(), 'priority', 'asc', $per_page);

        $orders->load(['skus' => function ($query) use ($daytime) {
            if ($daytime) {
                $query->where('weekday', Carbon::today()->dayOfWeek)->where('daytime', $daytime);
            }
            $query->where('weekday', Carbon::today()->dayOfWeek);
        }]);

        return $orders;
    }

    public function updatePreorderPriority($staff_id, $preorder_priority)
    {
        // TODO: Implement updatePreorderPriority() method.
    }


    public function updatePreorderChargeStatus($order_id, $charge_status)
    {
        $order = $this->get($order_id);
        $order->charge_status = $charge_status;
        $order->save();
        return $order;
    }

    public function updatePreorderStatus($order_id, $status)
    {
        $order = $this->get($order_id);
        $order->status = $status;
        $order->save();
        return $order;
    }

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null)
    {
        $order = $this->get($order_id);
        if (!is_null($station_id)) {
            $order->station_id = $station_id;
            $order->status = PreorderProtocol::ORDER_STATUS_OF_READY;
        }
        if (!is_null($staff_id)) {
            $order->staff_id = $staff_id;
        }
        $order->save();
        return $order;
    }
}
