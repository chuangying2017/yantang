<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\NoGenerator;
use App\Services\Order\OrderProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class AdminOrderRepositoryAbstract implements AdminOrderRepositoryContract {


    protected $type;

    public function __construct()
    {
        $this->setOrderType();
    }

    public function updateOrderStatus($order_no, $status)
    {
        $order = $this->getOrder($order_no, false);
        $order->status = $status;
        $order->save();

        return $order;
    }

    public function getOrder($order_no, $with_detail = true)
    {
        if ($order_no instanceof Order) {
            $order = $order_no;
        } else if (NoGenerator::isOrderNo($order_no)) {
            $order = Order::where('order_no', $order_no)->first();
        } else {
            $order = Order::find($order_no);
        }

        if (!$order) {
            throw new ModelNotFoundException();
        }

        if ($with_detail) {
            if ($this->isMallOrder()) {
                $order->load('skus', 'address', 'billings', 'billings.payment', 'deliver');
            } else {
                $order->load('skus', 'billings', 'billings.payment');
            }
        }

        return $order;
    }

    public function getAllOrders($start_time = null, $end_time = null, $time_name = 'pay_at', $status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc')
    {
        return $this->queryOrders($status, $keyword, $order_by, $sort, null, $start_time = null, $end_time = null, $time_name = 'pay_at');
    }

    public function getPaginatedOrders($status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE, $start_time = null, $end_time = null, $time_name = 'pay_at')
    {
        return $this->queryOrders($status, $keyword, $order_by, $sort, $per_page, $start_time = null, $end_time = null, $time_name = 'pay_at');
    }

    protected function queryOrders($status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc', $per_page = null, $start_time = null, $end_time = null, $time_name = 'pay_at')
    {
        $query = Order::query()->where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }

        $this->queryKeyword($query, $keyword);

        if (!is_null($start_time) || !is_null($end_time)) {
            $start_time = is_null($start_time) ? Carbon::create(2016, 8, 1) : $start_time;
            $end_time = is_null($end_time) ? Carbon::tomorrow() : $end_time;
            $query->whereBetween($time_name, [$start_time, $end_time]);
        }

        if ($this->isMallOrder()) {
            $query = $query->with('address');
        }

        $query->orderBy($order_by, $sort);

        if (is_null($per_page)) {
            return $query->get();
        }

        return $query->paginate($per_page);
    }


    protected function queryKeyword(&$query, $keyword = null)
    {
        if (!is_null($keyword)) {
            $query = $query->where(function ($query) use ($keyword) {
                $query->where('order_no', $keyword)
                    ->orWhereHas('address', function ($query) use ($keyword) {
                        $query->where(function ($query) use ($keyword) {
                            $query->where('phone', '=', $keyword)
                                ->orWhere('name', '=', $keyword);
                        });
                    });
            });
        }
    }

    protected abstract function setOrderType();

    /**
     * @return bool
     */
    protected function isMallOrder()
    {
        return $this->type == OrderProtocol::ORDER_TYPE_OF_MALL_MAIN;
    }
}


