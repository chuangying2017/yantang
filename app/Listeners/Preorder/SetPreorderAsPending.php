<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsAssigned;
use App\Models\Integral\IntegralRecord;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Repositories\Counter\StaffOrderCounterRepo;
use App\Repositories\Counter\StationOrderCounterRepo;
use App\Repositories\Integral\SignRule\SignClass;
use App\Repositories\Order\PreorderOrderRepository;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsPending {

    /**
     * @var PreorderRepositoryContract
     */
    private $orderRepo;
    /**
     * @var PreorderOrderRepository
     */
    private $preorderOrderRepository;

    /**
     * Create the event listener.
     *
     * @param PreorderRepositoryContract $orderRepo
     * @param PreorderOrderRepository $preorderOrderRepository
     */
    public function __construct(
        PreorderRepositoryContract $orderRepo,
        PreorderOrderRepository $preorderOrderRepository
    )
    {
        $this->orderRepo = $orderRepo;
        $this->preorderOrderRepository = $preorderOrderRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AssignIsAssigned $event
     * @return void
     */
    public function handle(AssignIsAssigned $event)
    {
        $assign = $event->assign;
        $preorder = $this->orderRepo->updatePreorderStatus($assign['preorder_id'], PreorderProtocol::ORDER_STATUS_OF_SHIPPING);
        $this->orderRepo->updatePreorderAssign($assign['preorder_id'], null, $assign['staff_id']);
        $order = $this->preorderOrderRepository->updateOrderStatusAsDeliver($preorder['order_id']);
        $this->buy_product_give($order);
    }

    public function buy_product_give(Order $order)
    {

        $total = 0;

        foreach ($order->skus as $key => $sku_val)
        {
            $product_data = Product::find($sku_val['product_id']);
            if (empty($product_data))
            {
                continue;
            }

            if ($product_data['product_double'] > 0)
            {
                $total += ($sku_val['pay_amount'] / 100) * $product_data['product_double'];//获取 对应 倍率 等于 获得积分
            }
        }

        if ($total >= 1)
        {
            $total = ceil($total);

            IntegralRecord::create(
                [
                    'type_id'      =>   $order->id,
                    'record_able'  =>   get_class($order),
                    'user_id'      =>   $order->user_id,
                    'name'         =>   SignClass::FETCH_BUY_PRODUCT,
                    'integral'     =>   '+' . $total
                ]
            );
        }
    }
}
