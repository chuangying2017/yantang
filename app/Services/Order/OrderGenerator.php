<?php namespace App\Services\Order;

use App\Events\Order\OrderIsCreated;
use App\Repositories\Cart\CartRepositoryContract;
use App\Repositories\Order\CampaignOrderRepository;
use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\MallClientOrderRepository;
use App\Services\Order\Generator\CalExpressFee;
use App\Services\Order\Generator\CalSkuAmount;
use App\Services\Order\Generator\CheckCampaign;
use App\Services\Order\Generator\CheckCoupon;
use App\Services\Order\Generator\GetOrderAddress;
use App\Services\Order\Generator\GetSkuInfo;
use App\Services\Order\Generator\GetUserInfo;
use App\Services\Order\Generator\SaveTempOrder;
use App\Services\Order\Generator\TempOrder;
use App\Services\Order\Generator\UseCampaign;
use App\Services\Order\Generator\UseCoupon;
use Cache;

class OrderGenerator implements OrderGeneratorContract {

    use OrderGeneratorHandler;
    /**
     * @var ClientOrderRepositoryContract
     */
    private $orderRepo;


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
    public function buy($user_id, $skus, $address_id = null, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN, $promotion_id = null)
    {
        if ($type == OrderProtocol::ORDER_TYPE_OF_CAMPAIGN) {
            return $this->campaignOrder($user_id, $skus, $promotion_id);
        }

        return $this->mallOrder($user_id, $skus, $address_id);
    }

    /**
     * @param $user_id
     * @param $cart_ids
     * @param $address_id
     * @return TempOrder
     */
    public function buyCart($user_id, $cart_ids, $address_id)
    {
        $carts = app(CartRepositoryContract::class)->getMany($cart_ids, false);
        $skus = [];
        foreach ($carts as $cart) {
            $skus[] = [
                'cart_id' => $cart['id'],
                'product_sku_id' => $cart['product_sku_id'],
                'quantity' => $cart['quantity']
            ];
        }
        return $this->buy($user_id, $skus, $address_id);
    }

    protected function campaignOrder($user_id, $skus, $campaign_id)
    {
        $config = [
            GetSkuInfo::class,
            GetUserInfo::class,
            CalSkuAmount::class,
//            UseCampaign::class,
            SaveTempOrder::class,
        ];


        $handler = $this->getOrderGenerateHandler($config);


        $temp_order = new TempOrder($user_id, $skus);
        $temp_order->setRequestPromotion($campaign_id);
        $temp_order = $handler->handle($temp_order);

        $this->setOrderRepo(app()->make(CampaignOrderRepository::class));
        $order = $this->orderRepo->createOrder($temp_order->toArray());

        return $order;
    }

    protected function mallOrder($user_id, $skus, $address)
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

        $this->setOrderRepo(app()->make(MallClientOrderRepository::class));
        $order = $this->orderRepo->createOrder($temp_order->toArray());

        event(new OrderIsCreated($temp_order));

        return $order;
    }

    /**
     * @param $temp_order_id
     * @param $coupon_id
     * @return TempOrder
     */
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


    public function setOrderRepo(ClientOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
}
