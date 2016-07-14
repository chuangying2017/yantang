<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\Support\PromotionAbleItemContract;

class TempOrder implements PromotionAbleItemContract {

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
        return [
            'temp_order_id' => $this->temp_order_id,
            'user' => $this->user,
            'skus' => $this->skus,
            'address' => $this->address,
            'total_amount' => $this->total_amount,
            'products_amount' => $this->products_amount,
            'discount_amount' => $this->discount_amount,
            'pay_amount' => $this->getPayAmount(),
            'express_fee' => $this->express_fee,
            'promotion' => $this->promotion,
            'error' => $this->error,
            'special_campaign' => $this->special_campaign,
            'preorder' => $this->preorder,
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
    public function getSkus()
    {
        return $this->skus;
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
     * @return mixed
     */
    public function getSkuAmount($sku_key)
    {
        return $this->skus[$sku_key]['total_amount'];
    }

    /**
     * @param mixed $sku_amount
     */
    public function setSkuAmount($sku_key, $sku_amount)
    {
        $this->skus[$sku_key]['total_amount'] = $sku_amount;
        $this->skus[$sku_key]['discount_amount'] = 0;
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
     * @param mixed $discount_amount
     */
    public function setDiscountAmount($discount_amount)
    {
        $this->discount_amount = $discount_amount;
    }

    public function init($items_data)
    {
        // TODO: Implement init() method.
    }

    public function getItems()
    {
        // TODO: Implement getItems() method.
    }

    public function getAmount($item_keys = null)
    {
        // TODO: Implement getAmount() method.
    }

    public function getItemsQuantity($item_keys = null)
    {
        // TODO: Implement getItemsQuantity() method.
    }

    public function getDiscountAmount()
    {
        // TODO: Implement getDiscountAmount() method.
    }

    public function getItemProductId($item_key)
    {
        // TODO: Implement getItemProductId() method.
    }

    public function getItemProductSkuID($item_key)
    {
        // TODO: Implement getItemProductSkuID() method.
    }

    public function getItemCategory($item_key)
    {
        // TODO: Implement getItemCategory() method.
    }

    public function getItemGroup($item_key)
    {
        // TODO: Implement getItemGroup() method.
    }

    public function setGifts($item_key = null)
    {
        // TODO: Implement setGifts() method.
    }

    public function unsetGifts($item_key = null)
    {
        // TODO: Implement unsetGifts() method.
    }

    public function getGifts($item_key = null)
    {
        // TODO: Implement getGifts() method.
    }

    public function setCredits()
    {
        // TODO: Implement setCredits() method.
    }

    public function unsetCredits()
    {
        // TODO: Implement unsetCredits() method.
    }

    public function getCredits()
    {
        // TODO: Implement getCredits() method.
    }

    public function setSkusDiscountAmount($item_keys, $discount_amount)
    {
        // TODO: Implement setSkusDiscountAmount() method.
    }

    public function unsetSkusDiscountAmount($item_keys, $discount_amount)
    {
        // TODO: Implement unsetSkusDiscountAmount() method.
    }

    public function getSkusDiscountAmount($item_keys)
    {
        // TODO: Implement getSkusDiscountAmount() method.
    }

    public function setSpecialPrice($special_price, $max_quantity = null, $qualify_text = null)
    {
        // TODO: Implement setSpecialPrice() method.
    }

    public function unsetSpecialPrice($special_price, $max_quantity = null, $qualify_text = null)
    {
        // TODO: Implement unsetSpecialPrice() method.
    }

    public function getSpecialPrice()
    {
        // TODO: Implement getSpecialPrice() method.
    }

    public function unsetDiscountAmount($discount_amount)
    {
        // TODO: Implement unsetDiscountAmount() method.
    }

    public function setDiscountExpressFee($discount_express_fee)
    {
        // TODO: Implement setDiscountExpressFee() method.
    }

    public function unsetDiscountExpressFee($discount_express_fee)
    {
        // TODO: Implement unsetDiscountExpressFee() method.
    }

    public function setRelateCoupons($rules)
    {
        // TODO: Implement setRelateCoupons() method.
    }

    public function unsetRelateCoupon($rule_key = null)
    {
        // TODO: Implement unsetRelateCoupon() method.
    }

    public function getRelateCoupons()
    {
        // TODO: Implement getRelateCoupons() method.
    }

    public function setRelateCampaigns($rules)
    {
        // TODO: Implement setRelateCampaigns() method.
    }

    public function unsetRelateCampaign($rule_key = null)
    {
        // TODO: Implement unsetRelateCampaign() method.
    }

    public function getRelateCampaigns()
    {
        // TODO: Implement getRelateCampaigns() method.
    }

    public function setUsableCampaigns($rule_key)
    {
        // TODO: Implement setUsableCampaigns() method.
    }

    public function unsetUsableCampaign($rule_key = null)
    {
        // TODO: Implement unsetUsableCampaign() method.
    }

    public function getUsableCampaigns()
    {
        // TODO: Implement getUsableCampaigns() method.
    }

    public function setUsableCoupons($rule_key)
    {
        // TODO: Implement setUsableCoupons() method.
    }

    public function unsetUsableCoupon($rule_key = null)
    {
        // TODO: Implement unsetUsableCoupon() method.
    }

    public function getUsableCoupons()
    {
        // TODO: Implement getUsableCoupons() method.
    }

    public function setCampaignBenefit($rule_key, $discount, $benefit)
    {
        // TODO: Implement setCampaignBenefit() method.
    }

    public function unsetCampaignBenefit($rule_key = null)
    {
        // TODO: Implement unsetCampaignBenefit() method.
    }

    public function setCouponBenefit($rule_key, $discount, $benefit)
    {
        // TODO: Implement setCouponBenefit() method.
    }

    public function unsetCouponBenefit($rule_key = null)
    {
        // TODO: Implement unsetCouponBenefit() method.
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

    public function get()
    {
        // TODO: Implement get() method.
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
}
