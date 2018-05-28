<?php namespace App\Repositories\Preorder;

use App\Events\Preorder\PreorderIsCancel;
use App\Models\Residence;
use App\Models\Billing\OrderBilling;
use App\Models\Pay\PingxxPayment;
use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\PreorderAssign;
use App\Repositories\NoGenerator;
use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Carbon\Carbon;
use App\Services\Preorder\PreorderProtocol;

class EloquentPreorderRepository implements PreorderRepositoryContract, StationPreorderRepositoryContract {

    public function createPreorder($data)
    {
        $residence_id = Residence::getResidenceIdByAddress($data['address'],$data['district_id']);
        $order = Preorder::create([
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id'],
            'order_no' => $data['order_no'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'address' => $data['address'],
            'station_id' => $data['station_id'],
            'district_id' => $data['district_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'weekday_type' => $data['weekday_type'],
            'daytime' => $data['daytime'],
            'total_amount' => $data['total_amount'],
            'status' => PreorderProtocol::ORDER_STATUS_OF_UNPAID,
            'residence_id' => $residence_id,
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

    public function getPaginatedByUser($user_id, $status = null, $start_time = null, $end_time = null, $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        $query = Preorder::with('skus');
		
        if (!is_null($user_id)) {
            $query->where('user_id', $user_id);
        }

        if (PreorderProtocol::validOrderStatus($status)) {
            $query->where('status', $status);
        }

        if (!is_null($start_time)) {
            $query->where('end_time', '>=', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('start_time', '<=', $end_time);
        }

        $query->orderBy('created_at', 'desc');

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    public function getAllByUser($user_id, $status = null, $start_time = null, $end_time = null)
    {
        $orders = $this->queryOrders($user_id, null, null, $status, $start_time, $end_time);
        $orders->load('skus');

        return $orders;
    }

    protected function queryOrders($user_id = null, $station_id = null, $staff_id = null, $status = null, $start_time = null, $end_time = null, $per_page = null, $orderBy = 'created_at', $sort = 'desc', $keyword = null)
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

        $this->scopeStatus($query, $status);

        if (!is_null($start_time)) {
            $query->where('end_time', '>=', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('start_time', '<=', $end_time);
        }

        if (!is_null($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('order_no', $keyword)
                    ->orWhere('phone', $keyword)
                    ->orWhere('name', $keyword);
            });
        }

        $query->orderBy($orderBy, $sort);

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    protected function queryByOrders($user_id = null, $station_id = null, $staff_id = null, $status = null, $start_time = null, $end_time = null, $time_name = 'created_at', $per_page = null, $orderBy = 'created_at', $sort = 'desc', $order_no = null, $phone = null, $pay_order_no = null, $invoice = null, $residence_id = null)
    {
        $query = Preorder::query();

        if (!is_null($user_id)) {
            $query->where('user_id', $user_id);
            
        }

        if (!is_null($station_id)) {
            if(is_array($station_id)) {
                $query->whereIn('station_id', $station_id);
            } else {
                $query->where('station_id', $station_id);
            }
            
        }
        
        if (!is_null($residence_id)) {
            if(count($residence_id) == 1 ){
                $residence_id = $residence_id[0];
            }
            if(is_array($residence_id)) {
                $query->whereIn('residence_id', $residence_id);
            } else {
                $query->where('residence_id', $residence_id);
            }
        }

        if (!is_null($staff_id)) {
            $query->where('staff_id', $staff_id);
        }

        if (!is_null($start_time)) {
            $query->where($time_name, '>=', $start_time)->cursor();
        }

        if (!is_null($end_time)) {
            $query->where($time_name, '<=', $end_time);
        }

        $keyword_tag = 0;

        if (!is_null($pay_order_no)) {
            $keyword_tag = 1;
            $billing_id = PingxxPayment::query()->where('payment_no', $pay_order_no)->pluck('billing_id')->first();

            $query->whereHas('billings', function ($query) use ($billing_id) {
                $query->where('id', $billing_id);
            });
        }

        if (!is_null($order_no)) {
            $keyword_tag = 1;
            $query->where('order_no', $order_no);
        }

        if (!is_null($phone)) {
            $keyword_tag = 1;
            $query->where('phone', $phone);
        }

        if (!$keyword_tag) {
            $this->scopeStatus($query, $status);
        }

        if (!is_null($invoice)) {
            $query->where('invoice', $invoice);
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
			//file_put_contents("test.txt",date("Y-m-d H:i:s")."\nlog=".json_encode("111")."\n\n");	
            $order = $preorder_id;
        } else if (strlen($preorder_id) == NoGenerator::LENGTH_OF_PREORDER_NO) {
			//file_put_contents("test.txt",date("Y-m-d H:i:s")."\nlog=".json_encode("222")."\n\n");	
            $order = Preorder::query()->where('order_no', $preorder_id)->firstOrFail();
        } else {
			//file_put_contents("test.txt",date("Y-m-d H:i:s")."\nlog=".json_encode("333")."\n\n");	
            $order = Preorder::query()->findOrFail($preorder_id);
        }
		//file_put_contents("test.txt",date("Y-m-d H:i:s")."\nlog=".json_encode($order)."\n\n");	
        if ($with_detail) {
            //查询赠品
            $order->load('skus', 'station', 'staff', 'user', 'order', 'order.promotions', 'tickets', 'tickets.coupon', 'redEnvelope', 'residence');
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

    public function getPreordersOfStation($station_id = null, $staff_id = null, $status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        $orders = $this->queryOrders(null, $station_id, $staff_id, $status, $start_time, $end_time, $per_page, $order_by, $sort);
        $orders->load('assign');

        return $orders;
    }

    public function getDayPreordersOfStation($station_id, $day = null, $daytime = null, $per_page = null)
    {
        $start_time = is_null($day) ? Carbon::today() : ($day instanceof Carbon ? $day : Carbon::createFromFormat('Y-m-d', $day));
        $end_time = $start_time;

        $orders = $this->getPreordersOfStation($station_id, null, PreorderProtocol::ORDER_STATUS_OF_SHIPPING, $start_time, $end_time, 'created_at', 'desc', $per_page);

        $orders->load(['skus' => function ($query) use ($start_time, $daytime) {
            if (!is_null($daytime)) {
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


    public function updatePreorderStatus($order_id, $status)
    {
        $order = $this->get($order_id);
        $order->status = $status;

        $time_tag = PreorderProtocol::statusTimeTag($status);
        if (!is_null($time_tag) && !$order->$time_tag) {
            $order->$time_tag = Carbon::now();
        }

        $order->save();
        return $order;
    }

    public function updatePreorderAssign($order_id, $station_id = null, $staff_id = null)
    {
        $order = $this->get($order_id);

        if ($station_id == 0 && $staff_id == 0) {
            $order->confirm_at = NULL;
        }

        if (!is_null($station_id)) {
            $order->station_id = $station_id;
        }
        if (!is_null($staff_id)) {
            $order->staff_id = $staff_id;
        }
        $order->save();
        return $order;
    }

    public function getPreordersOfStationNotConfirm($station_id)
    {
        return $this->queryOrders(null, $station_id, null, PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);
    }

    public function getPreordersOfStaff($staff_id, $status = null, $date = null, $daytime = null, $per_page = null)
    {
        $orders = $this->queryOrders(null, null, $staff_id, $status, null, null, $per_page, 'confirm_at', 'desc');
        $orders->load('skus');

        return $orders;
    }

    public function getDayPreorderWithProductsByStation($station_id, $date, $daytime = null)
    {
        $query_date = ($date instanceof Carbon) ? $date : Carbon::createFromFormat('Y-m-d', $date);
        //
        $query = Preorder::query()
            ->with(['skus' => function ($query) {
                $query->where('remain', '>', 0);
            }])
            ->where('station_id', $station_id)
            ->where('status', PreorderProtocol::ORDER_STATUS_OF_SHIPPING)//发货中
            ->whereBetween('start_time',[Carbon::createFromFormat('Y-m-d H:i:s',$date.'00:00:00'),$query_date])//Carbon::now()
            ->where(function ($query) use ($query_date) {
                //非暂停中
                $query->whereNull('pause_time')->orWhere('pause_time', '>', $query_date)
                    ->orWhere(function ($query) use ($query_date) {
                        $query->whereNotNull('restart_time')->where('restart_time', '<=', $query_date);
                    });
            });

        if (!is_null($daytime)) {
            $query->where('daytime', $daytime);
        }

        if ($query_date->isWeekend()) {
            $query->where('weekday_type', PreorderProtocol::WEEKDAY_TYPE_OF_ALL);
        }

        return $query->get();
    }

    public function getDayPreorderWithProductsOfStaff($staff_id, $date, $daytime = null)
    {
        $query_date = ($date instanceof Carbon) ? $date : Carbon::createFromFormat('Y-m-d', $date);
        $query = Preorder::query()
            ->with(['skus' => function ($query) {
                $query->where('remain', '>', 0);
            }])
            ->where('staff_id', $staff_id)
            ->where('status', PreorderProtocol::ORDER_STATUS_OF_SHIPPING)//发发货中
            ->where('start_time', '<=', $query_date)
            ->where(function ($query) use ($query_date) {
                //非暂停中
                $query->whereNull('pause_time')->orWhere('pause_time', '>', $query_date)
                    ->orWhere(function ($query) use ($query_date) {
                        $query->whereNotNull('restart_time')->where('restart_time', '<=', $query_date);
                    });
            });

        if (!is_null($daytime)) {
            $query->where('daytime', $daytime);
        }

        if ($query_date->isWeekend()) {
            $query->where('weekday_type', PreorderProtocol::WEEKDAY_TYPE_OF_ALL);
        }

        return $query->get();
    }

    public function getAllPaginated($station_id = null, $order_no = null, $pay_order_no = null, $phone = null, $order_status = null, $start_time = null, $end_time = null, $time_name = 'created_at', $invoice = null, $residence_id = null)
    {
        return $this->queryByOrders(
            null, $station_id, null,
            $order_status,
            $start_time, $end_time, $time_name,
            PreorderProtocol::PREORDER_PER_PAGE,
            'created_at', 'sort',
            $order_no, $phone, $pay_order_no,
            $invoice,
            $residence_id
        );
    }

    public function getAll($station_id = null, $order_no = null, $pay_order_no = null, $phone = null, $order_status = null, $start_time = null, $end_time = null, $time_name = 'created_at', $invoice = null, $residence_id = null)
    {
        
        return $this->queryByOrders(
            null, $station_id, null,
            $order_status,
            $start_time, $end_time, $time_name,
            null,
            'created_at', 'sort',
            $order_no, $phone, $pay_order_no,
            $invoice,
            $residence_id
        );
    }

    protected function scopeStatus($query, $order_status)
    {
        if(is_array($order_status)) {
            return $query->whereIn('status', $order_status);
        }

        if (PreorderProtocol::validOrderStatus($order_status) === true) {
            $query->where('status', $order_status);
        } else if (PreorderProtocol::validOrderAssignStatus($order_status) === true) {

            if (PreorderProtocol::validOrderAssignStatus($order_status, true) === true) {
                $query->where('status', PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);
            }

            //查询超时未处理订单

            $query->with('assign')->whereHas('assign', function ($query) use ($order_status) {
                if ($order_status == PreorderProtocol::ASSIGN_STATUS_OF_OVERTIME) {
                    $query->where('time_before', '<=', Carbon::now());
                    $order_status = PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED;
                }

                $query->where('status', $order_status);
            });

        } else {
            $query->whereNotIn('status', [
                PreorderProtocol::ORDER_STATUS_OF_UNPAID,
                PreorderProtocol::ORDER_STATUS_OF_CANCEL
            ]);
        }
    }

    public function updatePreorderStatusByOrder($order_id, $status)
    {
        $preorder = Preorder::query()->where('order_id', $order_id)->firstOrFail();
        $preorder->status = $status;

        if ($status == PreorderProtocol::ORDER_STATUS_OF_ASSIGNING) {
            $preorder->pay_at = Carbon::now();
        }

        $preorder->save();

        if ($status == PreorderProtocol::ORDER_STATUS_OF_CANCEL) {
            app()->make(PreorderAssignRepositoryContract::class)->deleteAssign($preorder['id']);
            event(new PreorderIsCancel($preorder));
        }

        return $preorder;
    }

    public function getByOrder($origin_order_id, $date = null)
    {
        if (!is_null($date)) {
            return Preorder::query()->where('start_time', '<=', $date)->where('end_time', '>=', $date)->first();
        }

        return Preorder::query()->where('order_id', $origin_order_id)->get();
    }

    public function updatePreorderTime($order_id, $pause_time = null, $restart_time = null)
    {
        $order = $this->get($order_id);
        if (!is_null($pause_time)) {
            $order->pause_time = $pause_time;
        }
        if (!is_null($restart_time)) {
            $order->restart_time = $restart_time;
        }

        $order->save();

        return $order;
    }

    public function getAllNotAssignOnTime($station_id = null, $per_page = false)
    {
        $query = Preorder::with('assign')->whereHas('assign', function ($query) {
            $query->where('status', PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED)->where('time_before', '<=', Carbon::now());
        })->where('status', PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);

        if (!is_null($station_id)) {
            $query->where('station_id', $station_id);
        }

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    public function getAllReject($station_id = null, $per_page = null)
    {
        $query = Preorder::with('assign')->whereHas('assign', function ($query) {
            $query->where('status', PreorderProtocol::ASSIGN_STATUS_OF_REJECT);
        })->where('status', PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);

        if (!is_null($station_id)) {
            $query->where('station_id', $station_id);
        }

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    public function changeTheStaffPreorders($old_staff_id, $new_staff_id)
    {
        Preorder::query()->where('staff_id', $old_staff_id)->update(['staff_id' => $new_staff_id]);
        PreorderAssign::query()->where('staff_id', $old_staff_id)->update(['staff_id' => $new_staff_id]);
    }

    public function updatePreorderAsDeliver($order_id)
    {
        $order = $this->get($order_id, false);
        $order->deliver_at = Carbon::now();
        $order->save();

        return $order;
    }

    public function getPreordersOfStationByKeyword($keyword, $station_id = null, $staff_id = null, $status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE)
    {
        $orders = $this->queryOrders(null, $station_id, $staff_id, $status, $start_time, $end_time, $per_page, $order_by, $sort, $keyword);
        $orders->load('assign');

        return $orders;
    }

    public function getPreordersOfStaffByKeyword($keyword, $staff_id, $status = null, $date = null, $daytime = null, $per_page = null)
    {
        $orders = $this->queryOrders(null, null, $staff_id, $status, null, null, $per_page, 'staff_priority', 'asc', $keyword);
        $orders->load('skus');

        return $orders;
    }

    public function getAllEnding($day = 3, $per_page = null)
    {
        $query = Preorder::query()->where('end_time', Carbon::today()->addDays($day))->where('status','<>','cancel');
        
        if($per_page) {
            return $query->paginate($per_page);
        }
        
        return $query->get();
    }

}
