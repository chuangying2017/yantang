<?php namespace App\Services\Order\Refund\Generator;

use App\Repositories\Order\ClientOrderRepository;
use App\Services\Order\OrderProtocol;

class GetAndCheckReferOrder extends RefundGenerateHandlerAbstract {

    /**
     * @var ClientOrderRepository
     */
    private $orderRepo;

    /**
     * RefundOrderGenerator constructor.
     * @param ClientOrderRepository $orderRepo
     */
    public function __construct(ClientOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function handle(TempRefundOrder $temp_order)
    {
        $order = $this->orderRepo->getOrder($temp_order->getReferOrder(), false);
        $temp_order->setReferOrder($order);

        if (!OrderProtocol::validStatusCanRefund($order['status'], $order['refund_status'])) {
            $temp_order->setError('订单当前无法退货退款');
            return $temp_order;
        }

        return $this->next($temp_order);
    }
}
