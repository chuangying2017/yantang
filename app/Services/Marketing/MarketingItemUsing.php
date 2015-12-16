<?php namespace App\Services\Marketing;

use App\Services\Marketing\Traits\MarketingItemResource;
use App\Services\Product\Category\CategoryService;
use Carbon\Carbon;

abstract class MarketingItemUsing implements MarketingInterface {

    use MarketingItemResource;

    protected $message;

    //用户使用优惠
    public abstract function used($id, $user_id);

    //列出用户可用优惠
    public abstract function usableList($user_id, $order_detail);

    //通过优惠id获取优惠额度
    public abstract function discountFee($ticket_id, $pay_amount);

    private static function decodeOrderInfo($order_info)
    {
        //处理商品信息
        $skus = [];
        foreach ($order_info['products'] as $sku) {
            $skus[ $sku['product_sku_id'] ] = [
                'product_sku_id' => $sku['product_sku_id'],
                'quantity'       => $sku['quantity'],
                'price'          => $sku['price'],
                'amount'         => bcmul($sku['price'], $sku['quantity'])
            ];
        }
        if ( ! count($skus)) {
            throw new \Exception('订单数据错误');
        }


        return [
            'total_amount'    => $order_info['total_amount'],
            'discount_fee'    => array_get($order_info, 'discount_fee', 0),
            'skus'            => $skus,
            'discount_detail' => array_get($order_info, 'discount_detail', []),
        ];

    }


    //查询优惠是否可用

    /**
     * 检查优惠是否能由于订单
     * @param $resource //给定优惠使用限制条件
     * @param $order_detail //订单商品详情
     * @return bool
     */
    public function filter($resource, $order_detail)
    {

        //检查优惠是否生效
        if ( ! self::checkEffectTime($resource)) {
            $this->setMessage(trans('marking.using.not_effect'));

            return false;
        }
        //检查优惠是否过期
        if ( ! self::checkExpireTime($resource)) {
            $this->setMessage(trans('marking.using.expired'));

            return false;
        }

        $order_detail = self::decodeOrderInfo($order_detail);

        $skus = $order_detail['skus'];

        //检查优惠是否可以叠加使用
        $discount_fee = array_get($order_detail, 'discount_fee', 0);
        if ( ! self::multiUse($resource, $discount_fee)) {
            $this->setMessage(trans('marking.using.multi_use'));

            return false;
        }

        //检查订单是否存在特定商品，若优惠券有商品限制且不存在该商品则不可使用优惠券
        $product_limit_pass = self::checkSku($resource, $skus);
        if ( ! is_null($product_limit_pass) && ! $product_limit_pass) {
            $this->setMessage(trans('marking.using.product_limit'));

            return false;
        }

        //检查订单需要支付金额是否达到要求
        $order_pay_amount = bcsub(array_get($order_detail, 'total_amount', 0), $discount_fee);
        if ($need_price = self::amountLimit($resource, $skus, $order_pay_amount)) {
            $this->setMessage(trans('marketing.using.amount_limit', ['price' => $need_price]));

            return false;
        }

        return true;
    }


    /**
     * 根据不同的优惠详类型，计算优惠额度
     * @param $discount_type
     * @param $discount_content
     * @param $pay_amount
     * @return int|string
     */
    protected function calculateDiscountFee($discount_type, $discount_content, $pay_amount)
    {
        if ($discount_type == MarketingProtocol::DISCOUNT_TYPE_OF_CASH) {
            return $discount_content > $pay_amount ? $pay_amount : $discount_content;
        }

        if ($discount_type == MarketingProtocol::DISCOUNT_TYPE_OF_DISCOUNT) {
            return bcdiv(bcmul($pay_amount, $discount_content), 1000, 0) ?: 0;
        }

        return 0;
    }

    public function lists($user_id, $status = MarketingProtocol::STATUS_OF_PENDING)
    {
        return MarketingRepository::userTicketsLists($user_id, $status, $this->getResourceType());
    }

    /**
     * 优惠可以叠加使用或者当前没有使用过优惠返回TRUE
     * @param $resource
     * @param $discount_fee
     * @return bool
     */
    protected static function multiUse($limit, $discount_fee)
    {
        return $limit['multi_use'] ? true : ($discount_fee > 0 ? false : true);
    }

    /**
     * 检查优惠是否生效
     * @param $limit
     * @return bool
     */
    protected static function checkEffectTime($limit)
    {
        return is_null($limit['effect_time']) ? 1 : Carbon::createFromFormat('Y-m-d H:i:s', strtotime($limit['effect_time'])) > Carbon::now();
    }

    /**
     * 检车优惠是否过期
     * @param $limit
     * @return bool
     */
    protected static function checkExpireTime($limit)
    {
        return is_null($limit['expired_time']) ? 1 : Carbon::createFromFormat('Y-m-d H:i:s', strtotime($limit['expired_time'])) < Carbon::now();
    }

    /**
     * 当前订单支付价格是否大于等于优惠要求优惠最低价格
     * @param $limit
     * @param $products
     * @param $order_pay_amount
     * @return bool
     * @internal param $order_total_amount
     */
    protected static function amountLimit($limit, $products, $order_pay_amount)
    {
        $order_pay_amount = self::calPayAmount($limit, $products, $order_pay_amount);

        return (bcsub($limit['amount_limit'], $order_pay_amount) > 0) ? bcsub($limit['amount_limit'], $order_pay_amount) : false;
    }

    /**
     * 计算限定品类商品的价格总额，一般用于价格总额限制值,如果没有限制则返回订单总额
     * @param $limit
     * @param $products
     * @return bool|int|string
     */
    protected static function calPayAmount($limit, $products, $order_pay_amount = 0)
    {
        $categories_products_price = 0;
        $parent_categories = MarketingProtocol::limitToArray($limit['category_limit'], 0);

        if ($parent_categories) {
            //存在品类限制，计算品类中的商品总价
            $categories = self::getFullCategories($parent_categories);
            foreach ($products as $key => $product) {
                if (in_array($product['category_id'], $categories)) {
                    $categories_products_price = bcadd($categories_products_price, $product['price']);
                }
            }
        } else {
            //已知订单总额
            if ( ! $order_pay_amount) {
                return $order_pay_amount;
            }
            //不存在品类限制，计算商品总价
            foreach ($products as $key => $product) {
                $categories_products_price = bcadd($categories_products_price, $product['price']);
            }
        }

        return $categories_products_price;
    }

    /**
     * 通过接口获取所有分类值
     * @param $categories
     * @return array
     */
    protected static function getFullCategories($categories)
    {
        $data = [];
        foreach ($categories as $category_id) {
            $data = array_merge($data, CategoryService::getLeavesId($category_id));
        }
        
        return $data;
    }

    /**
     * 判断订单中是否存在特定商品的优惠
     * @param $limit
     * @param $products
     * @return bool
     */
    protected static function checkSku($limit, $skus)
    {
        $limit_products = MarketingProtocol::limitToArray($limit['product_limit']);

        if (is_null($limit_products)) {
            return null;
        }

        //只需找到符合需求的sku优惠券就可用
        foreach ($skus as $sku) {
            if (in_array($sku['product_sku_id'], $limit_products)) {
                return true;
            }
        }

        return false;
    }

    public function show($ticket_id)
    {
        return MarketingRepository::showTicket($ticket_id);
    }

    /**
     * @param mixed $message
     * @return MarketingItemUsing
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


}
