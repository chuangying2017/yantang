<?php namespace App\Services\Order\Refund\Generator;

use App\Repositories\Order\Sku\OrderSkuRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class SetRefundSkus extends RefundGenerateHandlerAbstract {

    /**
     * @var OrderSkuRepositoryContract
     */
    private $skuRepo;

    /**
     * SetRefundSkus constructor.
     * @param OrderSkuRepositoryContract $skuRepo
     */
    public function __construct(OrderSkuRepositoryContract $skuRepo)
    {
        $this->skuRepo = $skuRepo;
    }

    public function handle(TempRefundOrder $temp_order)
    {
        if (!is_null($temp_order->getRefundSkus())) {
            $refund_order_skus = $this->transform($temp_order->getRefundSkus());
            $order_skus = $this->skuRepo->getOrderSkusByIds(array_keys($refund_order_skus));
        } else {
            $refer_order = $temp_order->getReferOrder();
            $order_skus = $this->skuRepo->getOrderSkus($refer_order['id']);

            $refund_order_skus = $this->transform($order_skus);
        }

        foreach ($order_skus as $key => $order_sku) {
            if ($order_sku['quantity'] - $order_sku['return_quantity'] < $refund_order_skus[$order_sku['id']]) {
                $temp_order->setError('退货退款商品数量超出购买量');
                return $temp_order;
            }

            $order_skus[$key]['quantity'] += $refund_order_skus[$order_sku['id']];
            $order_skus[$key]['pay_amount'] += $refund_order_skus[$order_sku['id']] * $order_sku['price'];
        }

        $temp_order->setRefundSkus($order_skus);

        return $this->next($temp_order);
    }

    protected function transform($refund_order_skus)
    {
        if ($refund_order_skus instanceof Collection) {
            $refund_order_skus = $refund_order_skus->toArray();
        }

        return array_pluck($refund_order_skus, 'quantity', 'product_sku_id');
    }


}
