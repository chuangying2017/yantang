<?php namespace App\Services\Order;

use App\Events\Order\OrderIsCreated;
use App\Repositories\Cart\CartRepositoryContract;
use App\Repositories\Order\CampaignOrderRepository;
use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\MallClientOrderRepository;
use App\Repositories\Order\PreorderOrderRepository;
use App\Repositories\Promotion\Campaign\EloquentCampaignRepository;
use App\Services\Order\Generator\CalExpressFee;
use App\Services\Order\Generator\CalSkuAmount;
use App\Services\Order\Generator\CheckCampaign;
use App\Services\Order\Generator\CheckCoupon;
use App\Services\Order\Generator\CheckGiftcard;
use App\Services\Order\Generator\GetOrderAddress;
use App\Services\Order\Generator\GetSkuInfo;
use App\Services\Order\Generator\GetSpecialCampaign;
use App\Services\Order\Generator\GetUserInfo;
use App\Services\Order\Generator\SaveTempOrder;
use App\Services\Order\Generator\SetPreorderInfo;
use App\Services\Order\Generator\TempOrder;
use App\Services\Order\Generator\UseCampaign;
use App\Services\Order\Generator\UseCoupon;
use App\Services\Order\Generator\UseGiftcard;
use App\Services\Order\Generator\DisableCoupon;
use App\Services\Order\Generator\DisableGiftcard;
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
    public function buy($user_id, $skus, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN)
    {
        if ($type == OrderProtocol::ORDER_TYPE_OF_MALL_MAIN) {
            return $this->mallOrder($user_id, $skus);
        }

    }

    /**
     * @param $user_id
     * @param $cart_ids
     * @param $address_id
     * @return TempOrder
     */
    public function buyCart($user_id, $cart_ids)
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
        return $this->buy($user_id, $skus);
    }

    public function buySpecialCampaign($user_id, $campaign_id, EloquentCampaignRepository $campaignRepo)
    {
        $product_skus_ids = $campaignRepo->getSku($campaign_id);
        $skus = [];
        foreach ($product_skus_ids as $product_sku_id) {
            $skus[] = ['product_sku_id' => $product_sku_id, 'quantity' => 1];
        }

        if (!count($skus)) {
            throw new \Exception('活动商品不存在, 请求错误');
        }

        return $this->campaignOrder($user_id, $skus, $campaign_id);
    }

    protected function campaignOrder($user_id, $skus, $campaign_id)
    {
        $config = [
            GetSkuInfo::class,
            GetUserInfo::class,
            CalSkuAmount::class,
            GetSpecialCampaign::class,
            SaveTempOrder::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = new TempOrder($user_id, $skus);
        $temp_order->setSpecialCampaign($campaign_id);

        $temp_order = $handler->handle($temp_order);

        if ($temp_order->getError()) {
            throw new \Exception($temp_order->getError());
        }

        $this->setOrderRepo(app()->make(CampaignOrderRepository::class));
        $order = $this->orderRepo->createOrder($temp_order->toArray());

        return $order;
    }

    protected function mallOrder($user_id, $skus)
    {
        $config = [
            GetSkuInfo::class,
            GetUserInfo::class,
            CalExpressFee::class,
            CalSkuAmount::class,
            CheckCampaign::class,
            CheckCoupon::class,
            CheckGiftcard::class,
            SaveTempOrder::class,
        ];
        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $handler->handle(new TempOrder($user_id, $skus));


        return $temp_order;
    }


    public function confirm($temp_order_id)
    {
        $temp_order = $this->pullTempOrder($temp_order_id);
        if (!$temp_order) {
            throw new \Exception('下单超时');
        }

        if ($temp_order->getError()) {
            throw new \Exception(json_encode($temp_order->getError()));
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
        $config = [
            UseCoupon::class,
            SaveTempOrder::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $this->pullTempOrder($temp_order_id);

        $temp_order->setRequestPromotion($coupon_id);

        $temp_order = $handler->handle($temp_order);

        return $temp_order;
    }

    public function useGiftcard($temp_order_id, $giftcard_id)
    {
        $config = [
            UseGiftcard::class,
            SaveTempOrder::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $this->pullTempOrder($temp_order_id);

        $temp_order->setRequestGiftcard($giftcard_id);

        $temp_order = $handler->handle($temp_order);

        return $temp_order;
    }

    /**
     * @param $temp_order_id
     * @return TempOrder
     */
    protected function pullTempOrder($temp_order_id)
    {
        $temp_order = Cache::pull($temp_order_id);
        return $temp_order;
    }

    public function getTempOrder($temp_order_id)
    {
        $temp_order = Cache::get($temp_order_id);
        return $temp_order;
    }

    public function setOrderRepo(ClientOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function setAddress($temp_order_id, $address)
    {
        $temp_order = $this->pullTempOrder($temp_order_id);

        $temp_order->setAddress($address);

        $config = [
            GetOrderAddress::class,
            SaveTempOrder::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = $handler->handle($temp_order);

        return $temp_order;
    }

    public function subscribe($user_id, $skus, $weekday_type, $daytime, $start_time, $address_id)
    {
        $config = [
            GetSkuInfo::class,
            GetUserInfo::class,
            GetOrderAddress::class,
            SetPreorderInfo::class,
            CalSkuAmount::class,
            CheckCoupon::class,
            CheckGiftcard::class,
            SaveTempOrder::class,
        ];

        $handler = $this->getOrderGenerateHandler($config);

        $temp_order = new TempOrder($user_id, $skus);
        $temp_order->setPreorder([
            'weekday_type' => $weekday_type,
            'daytime' => $daytime,
            'start_time' => $start_time
        ])->setAddress($address_id);

        $temp_order = $handler->handle($temp_order);

        if ($temp_order->getError()) {
            throw new \Exception(json_encode($temp_order->getError(), JSON_UNESCAPED_UNICODE));
        }

        return $temp_order;
    }

    public function confirmSubscribe($temp_order_id)
    {
        $temp_order = $this->pullTempOrder($temp_order_id);

        if (!$temp_order) {
            throw new \Exception('下单超时');
        }

        if ($temp_order->getError()) {
            throw new \Exception(json_encode($temp_order->getError()));
        }

        $this->setOrderRepo(app()->make(PreorderOrderRepository::class));
        $order = $this->orderRepo->createOrder($temp_order->toArray());

        return $order;
    }
}
