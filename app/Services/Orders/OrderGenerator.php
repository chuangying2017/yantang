<?php namespace App\Services\Orders;

use App\Services\Cart\CartService;
use App\Services\Marketing\MarketingItemDistributor;
use App\Services\Marketing\MarketingItemUsing;
use App\Services\Marketing\MarketingProtocol;
use Faker\Provider\Uuid;


class OrderGenerator {

    use ProductsHelper, OrderInfoHelpers;

    protected $marketingItemUsing;

    /**
     * @param MarketingItemUsing $marketingItemUsing
     * @internal param MarketingItemUsing $marketing
     */
    public function _construct(MarketingItemUsing $marketingItemUsing)
    {
        $this->marketingItemUsing = $marketingItemUsing;
    }

    public function buyCart($user_id, $carts)
    {
        $order_products_request['products'] = CartService::take($carts);

        return $this->buy($user_id, $order_products_request);
    }


    /**
     * 用户购买商品，返回订单数据
     * @param $user_id
     * @param $order_products_request
     * 格式
     * $order_products_request = [
     *  'products' => [
     *    [
     *      'product_sku_id' => 1,
     *      'quantity'       => 2,
     *      ]
     *      ]
     * ];
     * @return array
     *
     *
     *
     */
    public function buy($user_id, $order_products_request)
    {
        $products_info = self::checkProductSku($order_products_request['products']);
        $order_info = self::filterOrderProduct($products_info, $order_products_request['products']);
        $order_info['user_id'] = $user_id;
        $order_info['uuid'] = Uuid::uuid();

        $order_info['marketing']['coupons'] = $this->marketingItemUsing->usableList($user_id, $order_info);

        event('App\Services\Orders\Event\OrderRequest', $order_info);

        return $order_info;
    }

    public function requestDiscount($resource, $uuid)
    {
        $order_info = self::getOrder($uuid);

        if ($this->marketingItemUsing->filter($resource, $order_info)) {
            //查看能否使用
            $discount_fee = $this->marketingItemUsing->discountFee($resource, self::orderPayAmount($order_info));

            $order_info['discount_fee'] = bcadd($order_info['discount_fee'], $discount_fee);
            $resource['discount_fee'] = $discount_fee;

            #todo 修改订单优惠额，添加优惠项目
            //标记使用的优惠券
            if ($resource['resource_type'] == MarketingProtocol::TYPE_OF_COUPON) {
                $order_info['marketing']['coupons'] = self::orderUseCoupon($resource, $order_info);
            }
            #todo 多种优惠形式

        }

        $order_info['marketing']['coupons'] = $this->marketingItemUsing->usableList($order_info['user_id'], $order_info);

        event('App\Services\Orders\Event\OrderRequest', $order_info);

        return $order_info;
    }

    //标记使用的优惠券
    protected static function orderUseCoupon($resource, $order_info)
    {
        $find = 0;
        foreach ($order_info['marketing']['coupons'] as $key => $coupon) {
            if ($coupon['id'] == $resource['id']) {
                $find = 1;
                $order_info['marketing']['coupons'][ $key ]['selected'] = true;
                break;
            }
        }
        if ( ! $find) {
            $resource['selected'] = true;
            $order_info['marketing']['coupons'][] = $resource;
        }

        return $order_info['marketing']['coupons'];
    }

    protected static function orderPayAmount($order_info)
    {
        return bcsub($order_info['total_amount'], array_get($order_info, 'discount_fee', 0));
    }

    protected static function filterOrderProduct($products_info, $order_request_products)
    {

        $order_info = [
            'total_amount' => 0,
            'discount_fee' => 0,
            'products'     => []
        ];
        foreach ($products_info as $key => $product_info) {
            if (self::productCanAfford($product_info)) {
                $order_info['total_amount'] = bcadd($order_info['total_amount'], $product_info['price']);
                $order_info['products'][ $key ] = $product_info['data'];
                //加入商品购买数量
                foreach ($order_request_products as $request_key => $order_request_product) {
                    if ($order_request_product['product_sku_id'] == $product_info['product_sku_id']) {
                        $order_info['products'][ $key ]['quantity'] = $order_request_product['quantity'];
                        unset($order_request_products[ $request_key ]);
                        break;
                    }
                }
            }
        }

        return $order_info;
    }


    protected function checkProductSku($products)
    {
        $products_info = self::getProductSkuInfo($products);

        return $products_info;
    }

    public function confirm($uuid, $address)
    {
        $order_info = self::getOrder($uuid);

        try {
            $coupons = $this->doubleCheckCoupon($order_info);

            $order_info['address'] = $address;

            //生成订单，锁定
            $order_main = OrderRepository::generateOrder($order_info);

            event('App\Services\Orders\Event\OrderConfirm', [
                'order_id'   => $order_main['id'],
                'order_info' => $order_info
            ]);

            return $order_main;

        } catch (\Exception $e) {
            throw $e;
        }

    }

    protected function doubleCheckProduct($products)
    {
        $products_info = self::checkProductSku($products);
        $order_info = self::filterOrderProduct($products_info, $products);
        $checked_products = $order_info['products'];
        $afford_products = array_filter($checked_products, function ($product) {
            return $this->productCanAfford($product);
        });
        if (count($products) != count($afford_products)) {
            $can_not_afford_products = array_filter($checked_products, function ($product) {
                return ! $this->productCanAfford($product);
            });
            throw new \Exception('商品不可购买');
        }

        return $products;
    }

    protected function doubleCheckCoupon($order_info)
    {
        $coupons = array_get($order_info, 'marketing.coupons', []);
        if (count($coupons)) {
            foreach ($coupons as $key => $coupon) {
                if ( ! array_get($coupon, 'selected', false)) {
                    unset($coupons['key']);
                }
            }
        }
        $order_info['marketing']['coupons'] = $coupons;
        $checked_coupon = $this->marketingItemUsing->usableList($order_info['user_id'], $order_info);
        $use_able_coupon = array_filter($checked_coupon, function ($coupon) {
            return $coupon->can_use;
        });
        if (count($use_able_coupon) != count($coupons)) {
            $wrong_tickets = array_filter($checked_coupon, function ($coupon) {
                return ! $coupon->can_use;
            });
            logger($wrong_tickets);
            throw new \Exception('优惠券失效');
        }

        return $coupons;
    }


}
