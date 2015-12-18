<?php namespace App\Services\Orders;

use App\Services\Cart\CartService;
use App\Services\Client\AddressService;
use App\Services\Marketing\MarketingItemUsing;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Orders\Helpers\MarketingHelper;
use App\Services\Orders\Helpers\OrderInfoHelpers;
use App\Services\Orders\Helpers\ProductsHelper;
use Faker\Provider\Uuid;


class OrderGenerator {

    use OrderInfoHelpers, ProductsHelper, MarketingHelper;

    protected $marketingItemUsing = null;

    /**
     * @param MarketingItemUsing $marketingItemUsing
     * @internal param MarketingItemUsing $marketing
     */
    public function _construct(MarketingItemUsing $marketingItemUsing)
    {
        $this->marketingItemUsing = $marketingItemUsing;
    }

    /**
     * 查询购物车信息,
     * @param $user_id
     * @param $carts
     * @return array
     */
    public function buyCart($user_id, $carts)
    {
        $order_products_request = CartService::take($carts, $user_id);

        return $this->buy($user_id, $order_products_request, $carts);
    }

    /**
     * 用户购买商品，返回订单数据
     * @param $user_id
     * @param $order_products_request
     * 格式
     * $order_products_request = [
     *    [
     *      'product_sku_id' => 1,
     *      'quantity'       => 2,
     *      ]
     * ];
     * @return array
     */
    public function buy($user_id, $order_products_request, $carts = null)
    {
        try {
            $order_products_info = self::checkOrderSkus($order_products_request);

            $order_info = self::filterOrderSku($order_products_info['data']);
            $order_info = self::filterMarketingInfo($user_id, $order_info);

            $order_info['user_id'] = $user_id;
            $order_info['uuid'] = Uuid::uuid();
            $order_info['carts'] = $carts;

            event(new \App\Services\Orders\Event\OrderInfoChange($order_info));
            event(new \App\Services\Orders\Event\OrderRequest($order_info));

            return $order_info;
        } catch (\Exception $e) {
            throw new $e;
        }

    }

    private function filterMarketingInfo($user_id, $order_info)
    {
        #todo 加入多种优惠类型
        $order_info['marketing']['coupons'] = $this->coupon()->usableList($user_id, $order_info);
        $order_info['discount_detail']['coupons'] = array_get($order_info, 'discount_detail.coupons', []);

        return $order_info;
    }


    public function requestDiscount($resources, $uuid)
    {
        $order_info = self::getOrder($uuid);

        $order_info = self::removeDiscount($order_info);

        if ( ! count($resources)) {
            return $order_info;
        }

        if (count($resources) >= 1) {
            foreach ($resources as $resource) {
                $order_info = $this->orderUseDiscount($resource, $order_info);
            }
        }

        $order_info = self::filterMarketingInfo($order_info['user_id'], $order_info);

        $order_info['pay_amount'] = self::orderPayAmount($order_info);
        event(new \App\Services\Orders\Event\OrderInfoChange($order_info));

        return $order_info;
    }


    protected function orderUseDiscount($resource, $order_info)
    {
        if ($resource['resource_type'] == MarketingProtocol::TYPE_OF_COUPON) {

            //不可使用不存在的优惠券
            if ( ! isset($order_info['marketing']['coupons'][ $resource['id'] ])) {
                return $order_info;
            }

            if ($this->marketingItemUsing->filter($resource, $order_info)) {
                //查看能否使用
                $discount_fee = $this->marketingItemUsing->discountFee($resource, self::orderPayAmount($order_info));

                #todo 多种优惠形式
                //标记使用的优惠券
                $order_info['marketing']['coupons'][ $resource['id'] ]['discount_fee'] = $discount_fee;
                $order_info['marketing']['coupons'][ $resource['id'] ]['selected'] = true;
                $order_info['discount_detail']['coupons'][ $resource['id'] ] = $order_info['marketing']['coupons'][ $resource['id'] ];

                $order_info['discount_fee'] = bcadd($order_info['discount_fee'], $discount_fee);

            } else {
                //标记优惠券不可使用
                $order_info['marketing']['coupons'][ $resource['id'] ]['can_use'] = false;
                $order_info['marketing']['coupons'][ $resource['id'] ]['reason'] = '优惠不可使用';
            }
        }

        return $order_info;
    }

    protected static function removeDiscount($order_info)
    {
        if (count($order_info['marketing']['coupons'])) {
            foreach ($order_info['marketing']['coupons'] as $coupon) {
                if ($coupon['selected']) {
                    $order_info['discount_fee'] -= $coupon['discount_fee'];
                    $coupon['selected'] = false;
                    unset($order_info['discount_detail']['coupons'][ $coupon['id'] ]);
                }
            }
        }

        event(new \App\Services\Orders\Event\OrderInfoChange($order_info));

        if (self::orderPayAmount($order_info) <= 0) {
            event(new \App\Services\Orders\Event\OrderIsPaid($order_info['id']));
        }

        return $order_info;
    }


    protected static function orderPayAmount($order_info)
    {
        return (int)bcsub($order_info['total_amount'], array_get($order_info, 'discount_fee', 0));
    }

    protected static function filterOrderSku($order_skus_info)
    {
        $order_info = [
            'total_amount' => 0,
            'discount_fee' => 0,
            'products'     => [],
            'title'        => null
        ];

        foreach ($order_skus_info as $key => $order_sku_info) {

            $order_info['products'][ $key ] = $order_sku_info;
            $order_info['total_amount'] = (int)bcadd(
                $order_info['total_amount'],
                bcmul($order_sku_info['price'], $order_sku_info['quantity'])
            );

            $order_info['title'] = self::getOrderTitle($order_info['title'], $order_sku_info['title'], count($order_skus_info));
        }

        $order_info['pay_amount'] = self::orderPayAmount($order_info);

        return $order_info;
    }

    protected static function getOrderTitle($order_title, $product_title, $count)
    {
        if (is_null($order_title)) {
            if ($count > 1) {
                return $product_title . '等' . $count . '件商品';
            }

            return $product_title;
        }

        return $order_title;
    }


    public function fetchOrder($uuid)
    {
        $order_info = self::getOrder($uuid);

        return $order_info;
    }

    public function confirm($uuid, $address_id, $pay_type = OrderProtocol::PAY_ONLINE)
    {
        $order_info = self::getOrder($uuid);

        if ( ! $order_info) {
            throw new \Exception('等待时间过长,请重新下单');
        }

        try {
            $this->checkOrderSkus($order_info['products']);

            $order_info = $this->useCoupon()->doubleCheckCoupon($order_info);
            $order_info['address'] = AddressService::orderAddress($address_id);

            //生成订单，锁定
            $order_info['pay_type'] = $pay_type;

            $order_main = OrderRepository::generateOrder($order_info);

            event(new \App\Services\Orders\Event\OrderConfirm($order_main['id'], $order_info));

            $order_info = self::getOrder($uuid, true);

            return $order_main;

        } catch (\Exception $e) {
            throw $e;
        }

    }


    protected function checkProductSku($products)
    {
        $products_info = self::getProductSkuInfo($products);

        return $products_info;
    }


    protected static function checkOrderSkus($order_skus_info)
    {
        $order_products_info = self::getProductSkuInfo($order_skus_info);

        if ( ! $order_products_info['success']) {
            throw new \Exception($order_products_info['message']);
        }

        return $order_products_info;
    }

    protected function doubleCheckCoupon($order_info)
    {
        return $this->requestDiscount(array_get($order_info, 'discount_detail.coupons', []), $order_info['uuid']);
    }


}
