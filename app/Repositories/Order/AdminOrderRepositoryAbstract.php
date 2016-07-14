<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\NoGenerator;
use App\Services\Order\OrderProtocol;
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
                $order->load('skus', 'address', 'billings', 'billings.payment');
            } else {
                $order->load('skus', 'billings', 'billings.payment');
            }
        }

        return $order;
    }

    public function getAllOrders($status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc')
    {
        return $this->queryOrders($status, $keyword, $order_by, $sort);
    }

    public function getPaginatedOrders($status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        return $this->queryOrders($status, $keyword, $order_by, $sort, $per_page);
    }

    protected function queryOrders($status = null, $keyword = null, $order_by = 'created_at', $sort = 'desc', $per_page = null)
    {
        $query = Order::where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }


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


        if ($this->isMallOrder()) {
            $query = $query->with('address');
        }


        if (is_null($per_page)) {
            return $query->get();
        }

        return $query->paginate($per_page);
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


