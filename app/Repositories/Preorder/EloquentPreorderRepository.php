<?php namespace App\Repositories\Preorder;

use App\Events\Preorder\PreorderIsCancel;
use App\Models\Subscribe\Preorder;
use App\Repositories\NoGenerator;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Carbon\Carbon;
use App\Services\Preorder\PreorderProtocol;

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
            'status' => PreorderProtocol::ORDER_STATUS_OF_ASSIGNING,
            'charge_status' => PreorderProtocol::CHARGE_STATUS_OF_NULL,
        ]);

        app()->make(PreorderAssignRepositoryContract::class)->createAssign($order['id'], $data['station_id']);

        return $order;
    }

    public function updatePreorder($order_id, $data)
    {
        $order = $this->get($order_id);

        if ($order['status'] !== PreorderProtocol::ORDER_STATUS_OF_ASSIGNING) {
            throw new \Exception('订单无法修改', 400);
        }

        $order['name'] = $data['name'];
        $order['phone'] = $data['phone'];
        $order['district_id'] = $data['district_id'];
        $order['address'] = $data['address'];
        $order['station_id'] = $data['station_id'];
        $order->save();

        return $order;
    }

    public function updatePreorderByStation($order_id, $start_time = null, $end_time = null, $product_skus = null, $station_id = null)
    {
        $order = $this->get($order_id);

        if (!is_null($start_time)) {
            $order->start_time = $start_time;
        }

        if (!is_null($end_time)) {
            $order->end_time = $end_time;
        }

        if (!is_null($station_id)) {
            $order->station_id = $station_id;
        }

        $order->save();

        if (!is_null($product_skus)) {
            $preorder_sku_repo = app()->make(PreorderSkusRepositoryContract::class);
            $preorder_sku_repo->deletePreorderProducts($order_id);
            $order->skus = $preorder_sku_repo->createPreorderProducts($order_id, $product_skus);
        }

        return $order;
    }

    public function getPaginatedByUser($user_id, $status = null, $start_time = null, $end_time = null, $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        return $this->queryOrders($user_id, null, null, $status, null, $start_time, $end_time, PreorderProtocol::PREORDER_PER_PAGE);
    }

    public function getAllByUser($user_id, $status = null, $start_time = null, $end_time = null)
    {
        return $this->queryOrders($user_id, null, null, $status, null, $start_time, $end_time);
    }

    protected function queryOrders($user_id = null, $station_id = null, $staff_id = null, $status = null, $charge_status = null, $start_time = null, $end_time = null, $per_page = null, $orderBy = 'created_at', $sort = 'desc')
    {
        $query = Preorder::query();


        if (!is_null($user_id)) {
            $query->where('user_id', $user_id);
        }


        if (!is_null($station_id)) {
            $query->where('station_id', $station_id);
        }


        if (!is_null($staff_id)) {
            $query->where('staff_id', $staff_id);
        }


        if (PreorderProtocol::validOrderStatus($status)) {
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

    /**
     * @param $preorder_id
     * @param bool|false $with_detail
     * @return Preorder
     */
    public function get($preorder_id, $with_detail = false)
    {
        if ($preorder_id instanceof Preorder) {
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
        $order = $this->get($preorder_id);
        $order->status = PreorderProtocol::ORDER_STATUS_OF_CANCEL;
        $order->save();

        event(new PreorderIsCancel($order));

        return $order;
    }

    public function getPreordersOfStation($station_id = null, $staff_id = null, $status = null, $charge_status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        return $this->queryOrders(null, $station_id, $staff_id, $status, $charge_status, $start_time, $end_time, $per_page, $order_by, $sort);
    }

    public function getDayPreordersOfStation($station_id, $day = null, $daytime = null, $per_page = null)
    {
        $start_time = is_null($day) ? Carbon::today() : ($day instanceof Carbon ? $day : Carbon::createFromFormat('Y-m-d', $day));
        $end_time = $start_time;

        $orders = $this->getPreordersOfStation($station_id, null, PreorderProtocol::ORDER_STATUS_OF_SHIPPING, PreorderProtocol::CHARGE_STATUS_OF_OK, $start_time, $end_time, 'created_at', 'desc', $per_page);

        $orders->load(['skus' => function ($query) use ($start_time, $daytime) {
            if ($daytime) {
                $query->where('weekday', $start_time->dayOfWeek)->where('daytime', $daytime);
            }
            $query->where('weekday', $start_time->dayOfWeek);
        }]);

        return $orders;
    }

    public function updatePreorderPriority($order_id, $staff_id, $preorder_priority)
    {
        $order = $this->get($order_id);

        if ($order['staff_id'] !== $staff_id) {
            throw new \Exception('没有权限修改', 403);
        }

        $order->staff_priority = $preorder_priority;
        $order->save();

        return $order;
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
            $order->status = PreorderProtocol::ORDER_STATUS_OF_SHIPPING;
        }
        if (!is_null($staff_id)) {
            $order->staff_id = $staff_id;
        }
        $order->save();
        return $order;
    }


    public function getPreordersOfStationNotConfirm($station_id)
    {
        return $this->queryOrders(null, $station_id, null, PreorderProtocol::ORDER_STATUS_OF_ASSIGNING, PreorderProtocol::CHARGE_STATUS_OF_OK);
    }


    public function getDayPreordersOfStaff($staff_id = null, $day = null, $daytime = null, $per_page = null)
    {
        $start_time = is_null($day) ? Carbon::today() : $day;
        $end_time = is_null($day) ? Carbon::today() : $day;

        $orders = $this->getPreordersOfStation(null, $staff_id, PreorderProtocol::ORDER_STATUS_OF_SHIPPING, PreorderProtocol::CHARGE_STATUS_OF_OK, $start_time, $end_time, 'staff_priority', 'asc', $per_page);


        $orders->load(['skus' => function ($query) use ($daytime) {
            if ($daytime) {
                $query->where('weekday', Carbon::today()->dayOfWeek)->where('daytime', $daytime);
            }
            $query->where('weekday', Carbon::today()->dayOfWeek);
        }]);

        foreach ($orders as $key => $order) {
            if (!count($order->skus)) {
                unset($orders[$key]);
            }
        }

        return $orders;
    }

    public function getDayPreorderWithProductsByStation($station_id, $day, $daytime = null)
    {
        $query_day = ($day instanceof Carbon) ? $day : Carbon::createFromFormat('Y-m-d', $day);

        return Preorder::query()
            ->with(['skus' => function ($query) use ($query_day, $daytime) {
                if ($daytime) {
                    $query->where('weekday', $query_day->dayOfWeek)->where('daytime', $daytime);
                } else {
                    $query->where('weekday', $query_day->dayOfWeek);
                }
            }])
            ->where('station_id', $station_id)
            ->where('status', PreorderProtocol::ORDER_STATUS_OF_SHIPPING)//发发货中
            ->where('charge_status', PreorderProtocol::CHARGE_STATUS_OF_OK)//已充值
            ->where('start_time', '<=', $day)
            ->where('end_time', '>=', $day)//有效期内
            ->get(['id', 'user_id', 'station_id', 'staff_id']);
    }

    public function getDayPreorderWithProductsOfStaff($staff_id, $day, $daytime = null)
    {
        $query_day = ($day instanceof Carbon) ? $day : Carbon::createFromFormat('Y-m-d', $day);
        return Preorder::query()
            ->where('staff_id', $staff_id)
            ->where('status', PreorderProtocol::ORDER_STATUS_OF_SHIPPING)//发发货中
            ->where('charge_status', PreorderProtocol::CHARGE_STATUS_OF_OK)//已充值
            ->where('start_time', '<=', $day)
            ->where('end_time', '>=', $day)//有效期内
            ->whereHas('skus', function ($query) use ($query_day, $daytime) {
                if ($daytime) {
                    $query->where('weekday', Carbon::today()->dayOfWeek)->where('daytime', $daytime);
                }
                $query->where('weekday', Carbon::today()->dayOfWeek);
            })->get();
    }
}
