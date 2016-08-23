<?php namespace App\Services\Order\Generator;

use App\Repositories\Product\Brand\EloquentBrandRepository;
use App\Repositories\Product\Cat\EloquentCategoryRepository;
use App\Repositories\Product\Group\EloquentGroupRepository;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Benefit\Setter\PromotionAbleItemTrait;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class TempOrder implements PromotionAbleItemContract {

    use PromotionAbleItemTrait;

    protected $carts;
    protected $temp_order_id;
    protected $address;
    protected $total_amount;
    protected $express_fee = 0;
    protected $user;
    protected $skus;
    protected $promotion;
    protected $error;

    protected $products_amount = 0;
    protected $discount_amount = 0;

    protected $special_campaign;
    protected $request_promotion;

    protected $preorder;

    public function __construct($user_id, $skus, $address = null)
    {
        $this->setUser($user_id);
        $this->setSkus($skus);
        $this->setCarts();
        $this->setAddress($address);
        $this->setTempOrderId('temp_' . generate_no());
    }

    public function toArray()
    {
        $this->updateSkusPayAmount();
        return [
            'temp_order_id' => $this->temp_order_id,
            'user' => $this->user,
            'skus' => $this->skus,
            'address' => $this->address,
            'total_amount' => $this->total_amount,
            'products_amount' => $this->products_amount,
            'discount_amount' => $this->getDiscountAmount(),
            'pay_amount' => $this->getPayAmount(),
            'express_fee' => $this->express_fee,
            'discount_express_fee' => $this->promotion_express_fee,
            'error' => $this->error,
            'special_campaign' => $this->special_campaign,
            'preorder' => $this->preorder,
            'promotions' => $this->promotions,
            'rules' => $this->getRules(),
            'coupons' => $this->showCoupons(),
            'campaigns' => $this->getCampaigns(),
        ];
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

    /**
     * @return mixed
     */
    public function getExpressFee()
    {
        return $this->express_fee;
    }

    /**
     * @param mixed $express_fee
     */
    public function setExpressFee($express_fee)
    {
        $this->express_fee = $express_fee;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getSkus($sku_key = null)
    {
        return is_null($sku_key) ? $this->skus : $this->skus[$sku_key];
    }

    /**
     * @param mixed $skus
     */
    public function setSkus($skus)
    {
        $this->skus = $skus;
    }

    /**
     * @return mixed
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param mixed $promotion
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @param $sku_key
     * @param bool $discount
     * @return mixed
     */
    public function getSkuAmount($sku_key, $discount = false)
    {
        if ($discount) {
            return $this->skus[$sku_key]['total_amount'] - $this->skus[$sku_key]['discount_amount'];
        }
        return $this->skus[$sku_key]['total_amount'];
    }

    public function updateSkusPayAmount()
    {
        $this->products_amount = 0;
        foreach ($this->skus as $sku_key => $sku) {
            $this->skus[$sku_key]['pay_amount'] = $this->getSkuAmount($sku_key, true);
            $this->products_amount += $this->skus[$sku_key]['pay_amount'];
        }

        return $this;
    }

    /**
     * @param mixed $sku_amount
     */
    public function setSkuAmount($sku_key, $sku_amount, $discount_amount = 0)
    {
        $this->skus[$sku_key]['total_amount'] = $sku_amount;
        $this->skus[$sku_key]['origin_total_amount'] = $sku_amount;
        $this->skus[$sku_key]['discount_amount'] = $discount_amount;
        $this->skus[$sku_key]['pay_amount'] = $sku_amount;
    }


    /**
     * @param string $temp_order_id
     * @return TempOrder
     */
    public function setTempOrderId($temp_order_id)
    {
        $this->temp_order_id = $temp_order_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempOrderId()
    {
        return $this->temp_order_id;
    }

    /**
     * @param mixed $error
     * @return TempOrder
     */
    public function setError($error, $over_write = false)
    {
        if ($over_write) {
            $this->error = [$error];
        } else {
            $this->error[] = $error;
        }
    }

    public function getError()
    {
        return is_null($this->error) ? null : json_encode($this->error);
    }

    /**
     * @param mixed $products_amount
     */
    public function setProductsAmount($products_amount)
    {
        $this->products_amount = $products_amount;
    }


    /**
     * @param mixed $request_promotion
     * @return TempOrder
     */
    public function setRequestPromotion($request_promotion)
    {
        $this->request_promotion = $request_promotion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestPromotion()
    {
        return $this->request_promotion;
    }

    /**
     * @return mixed
     */
    public function getPayAmount()
    {
        return bcsub($this->total_amount, $this->discount_amount, 0);
    }

    /**
     * @param mixed $carts
     * @return TempOrder
     */
    protected function setCarts()
    {
        $carts = [];
        foreach ($this->skus as $sku) {
            $carts[] = array_get($sku, 'cart_id', null);
        }
        if (count($carts)) {
            $this->carts = $carts;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * @return mixed
     */
    public function getSpecialCampaign()
    {
        return $this->special_campaign;
    }

    /**
     * @param mixed $special_campaign
     */
    public function setSpecialCampaign($special_campaign)
    {
        $this->special_campaign = $special_campaign;
    }

    /**
     * @param mixed $preorder
     * @return TempOrder
     */
    public function setPreorder($preorder)
    {
        $this->preorder = $preorder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreorder($key = null)
    {
        return is_null($key) ? $this->preorder : $this->preorder[$key];
    }


    public function getItems($sku_keys = null)
    {
        if (is_null($sku_keys)) {
            return $this->getSkus();
        }

        $items = [];
        foreach ($sku_keys as $sku_key) {
            $items[$sku_key] = $this->getSkus($sku_key);
        }

        return $items;
    }

    public function getAmount($item_keys = null)
    {
        if (is_null($item_keys)) {
            return $this->getPayAmount();
        } else if (is_array($item_keys)) {
            $amount = 0;
            foreach ($item_keys as $item_key) {
                $amount += $this->getSkuAmount($item_key, true);
            }
            return $amount;
        } else {
            return $this->getSkuAmount($item_keys, true);
        }
    }

    public function getItemsQuantity($item_keys = null)
    {
        $quantity = 0;

        if (is_null($item_keys)) {
            foreach ($this->getSkus() as $sku) {
                $quantity += $sku['quantity'];
            }
        } else if (is_array($item_keys)) {
            foreach ($item_keys as $item_key) {
                $sku = $this->getSkus($item_key);
                $quantity += $sku['quantity'];
            }
        } else {
            $sku = $this->getSkus($item_keys);
            $quantity += $sku['quantity'];
        }

        return $quantity;
    }

    public function getDiscountAmount()
    {
        return $this->discount_amount;
    }

    public function getItemProductId($item_key)
    {
        $sku = $this->getSkus($item_key);
        return $sku['product_id'];
    }

    public function getItemProductSkuID($item_key)
    {
        $sku = $this->getSkus($item_key);
        return $sku['id'];
    }

    public function getItemCategory($item_key)
    {
        app()->make(EloquentCategoryRepository::class)->getByIdProducts($this->getItemProductId($item_key));
    }

    public function getItemBrand($item_key)
    {
        app()->make(EloquentBrandRepository::class)->getByIdProducts($this->getItemProductId($item_key));
    }

    public function getItemGroup($item_key)
    {
        app()->make(EloquentGroupRepository::class)->getByIdProducts($this->getItemProductId($item_key));
    }

    public function getSkuPriceTag()
    {
        return $this->preorder ? 'subscribe_price' : 'price';
    }

    public function setDiscountAmount($amount, $action = PromotionProtocol::ACTION_OF_ADD)
    {
        if ($action === PromotionProtocol::ACTION_OF_ADD) {
            $this->discount_amount += $amount;
        } else if ($action === PromotionProtocol::ACTION_OF_SUB) {
            $this->discount_amount -= $amount;
        } else {
            $this->discount_amount = $amount;
        }
    }


    public function setProductDiscount($sku_id, $amount, $action = PromotionProtocol::ACTION_OF_ADD)
    {
        $need_set_sku_key = $this->findSkuKeyBySkuId($sku_id);

        if (is_null($need_set_sku_key)) {
            return false;
        }

        if ($action === PromotionProtocol::ACTION_OF_ADD) {
            $this->skus[$need_set_sku_key]['discount_amount'] += $amount;
        } else if ($action === PromotionProtocol::ACTION_OF_SUB) {
            $this->skus[$need_set_sku_key]['discount_amount'] -= $amount;
        }

        $this->setDiscountAmount($amount, $action);

        return true;
    }

    protected function findSkuKeyBySkuId($sku_id)
    {
        $need_set_sku_key = null;
        foreach ($this->skus as $sku_key => $sku) {
            if ($sku['id'] == $sku_id) {
                $need_set_sku_key = $sku_key;
            }
        }

        return $need_set_sku_key;
    }


    public function setPromotionProducts($add_sku, $action = PromotionProtocol::ACTION_OF_ADD)
    {
        $sku_key = $this->findSkuKeyBySkuId($add_sku['id']);

        if ($action == PromotionProtocol::ACTION_OF_ADD) {

            if (is_null($sku_key)) {
                $this->skus[] = $add_sku;
            } else {
                $this->skus[$sku_key]['quantity'] += $add_sku['quantity'];
                $this->skus[$sku_key]['discount_amount'] += $add_sku['discount_amount'];
                $this->skus[$sku_key]['total_amount'] += $add_sku['discount_amount'];
            }
            $this->total_amount += $add_sku['total_amount'];

        } else {

            if ($add_sku['quantity'] < $this->skus[$sku_key]['quantity']) {
                $this->skus[$sku_key]['quantity'] -= $add_sku['quantity'];
                $this->skus[$sku_key]['discount_amount'] -= $add_sku['discount_amount'];
            } else {
                unset($this->skus[$sku_key]);
            }
            $this->total_amount -= $add_sku['total_amount'];
            
        }

        $this->setDiscountAmount($add_sku['discount_amount'], $action);
    }
}
