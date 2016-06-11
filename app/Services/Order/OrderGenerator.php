<?php namespace App\Services\Order;

use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Services\Order\Generator\CalExpressFee;
use App\Services\Order\Generator\CalSkuAmount;
use App\Services\Order\Generator\CheckCampaign;
use App\Services\Order\Generator\CheckCoupon;
use App\Services\Order\Generator\GetOrderAddress;
use App\Services\Order\Generator\GetSkuInfo;
use App\Services\Order\Generator\GetUserInfo;
use App\Services\Order\Generator\SaveTempOrder;
use App\Services\Order\Generator\TempOrder;
use App\Services\Order\Generator\UseCoupon;
use Cache;

class OrderGenerator implements OrderGeneratorContract {

    use OrderGeneratorHandler;
    /**
     * @var ClientOrderRepositoryContract
     */
    private $orderRepo;

    /**
     * OrderGenerator constructor.
     * @param ClientOrderRepositoryContract $orderRepo
     */
    public function __construct(ClientOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param $user_id
     * @param $skus
     * $skus = [
     *  [
     *      'product_sku_id' => 1,
     *      'quantity' => 1
     *  ]
     * ]
     */
    public function buy($user_id, $skus, $address = null, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN)
    {
        $config = [
            GetSkuInfo::class,
            GetUserInfo::class,
            GetOrderAddress::class,
            CalExpressFee::class,
            CalSkuAmount::class,
            CheckCampaign::class,
            CheckCoupon::class,
            SaveTempOrder::class,
        ];
        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $handler->handle(new TempOrder($user_id, $skus, $address));

        return $temp_order;
    }


    public function confirm($temp_order_id)
    {
        $temp_order = $this->getTempOrder($temp_order_id);
        if (!$temp_order) {
            throw new \Exception('下单超时');
        }

        $order = $this->orderRepo->createOrder($temp_order);

        return $order;
    }

    public function useCoupon($temp_order_id, $coupon_id)
    {
        #todo 传输coupon值
        $config = [
            UseCoupon::class,
            SaveTempOrder::class,
        ];
        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $this->getTempOrder($temp_order_id);

        $temp_order = $handler->handle($temp_order);

        return $temp_order;
    }

    /**
     * @param $temp_order_id
     * @return mixed
     */
    protected function getTempOrder($temp_order_id)
    {
        $temp_order = Cache::pull($temp_order_id);
        return $temp_order;
    }

    protected function saveTempOrder(TempOrder $temp_order)
    {
        $temp_order_id = $temp_order->getTempOrderId();
        if (Cache::has($temp_order_id)) {
            Cache::forget($temp_order_id);
            Cache::put($temp_order_id, $temp_order, 30);
        }
    }
}
