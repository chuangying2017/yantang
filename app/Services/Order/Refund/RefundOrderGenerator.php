<?php namespace App\Services\Order\Refund;

use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\RefundClientOrderRepository;
use App\Services\Order\OrderGeneratorHandler;
use App\Services\Order\Refund\Generator\CalRefundAmount;
use App\Services\Order\Refund\Generator\GetAndCheckReferOrder;
use App\Services\Order\Refund\Generator\SaveTempRefund;
use App\Services\Order\Refund\Generator\SetRefundSkus;
use App\Services\Order\Refund\Generator\TempRefundOrder;

class RefundOrderGenerator {

    use OrderGeneratorHandler;

    /**
     * @var ClientOrderRepositoryContract
     */
    protected $orderRepo;

    /**
     * @param $order_no
     * @param null $order_skus
     * @param string $memo
     * @return TempRefundOrder
     */
    public function refund($order_no, $order_skus = null, $memo = '')
    {
        $config = [
            GetAndCheckReferOrder::class,
            SetRefundSkus::class,
            CalRefundAmount::class,
            SaveTempRefund::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_refund = $handler->handle(new TempRefundOrder($order_no, $order_skus, $memo));

        return $this->confirm($temp_refund->getTempOrderId());
    }

    public function confirm($temp_order_id)
    {
        $temp_order = $this->pullTempOrder($temp_order_id);
        if (!$temp_order) {
            throw new \Exception('确认超时');
        }

        if ($temp_order->getError()) {
            throw new \Exception(json_encode($temp_order->getError()));
        }

        if ($temp_order->getPayAmount() <= 0) {
            throw new \Exception('退款金额为0,无法发起退款');
        }

        $this->setOrderRepo(app()->make(RefundClientOrderRepository::class));
        $order = $this->orderRepo->createOrder($temp_order->toArray());

        return $order;
    }

    public function setOrderRepo(ClientOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param $temp_order_id
     * @return TempRefundOrder
     */
    protected function pullTempOrder($temp_order_id)
    {
        $temp_order = \Cache::pull($temp_order_id);
        return $temp_order;
    }

    public function getTempOrder($temp_order_id)
    {
        $temp_order = \Cache::get($temp_order_id);
        return $temp_order;
    }

}
