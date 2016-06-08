<?php namespace App\Repositories\Promotion;

use App\Models\Models\Promotion\UserPromotion;
use App\Models\Promotion\Coupon;
use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use Cache;

class CachePromotionSupportRepository implements PromotionSupportRepositoryContract {

    const CACHE_KEY_OF_CAMPAIGNS = 'YT_CURRENT_CAMPAIGNS';

    /**
     * @var CampaignRepositoryContract
     */
    private $campaignRepo;
    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * CachePromotionSupportRepository constructor.
     * @param CampaignRepositoryContract $campaignRepo
     * @param CouponRepositoryContract $couponRepo
     */
    public function __construct(CampaignRepositoryContract $campaignRepo, CouponRepositoryContract $couponRepo)
    {
        $this->campaignRepo = $campaignRepo;
        $this->couponRepo = $couponRepo;
    }

    public function getCampaigns()
    {
        if (Cache::has(self::CACHE_KEY_OF_CAMPAIGNS)) {
            return Cache::get(self::CACHE_KEY_OF_CAMPAIGNS);
        }

        return $this->refreshCampaigns();
    }

    public function getCampaign($campaign_id)
    {
        if (is_int($campaign_id)) {
            $campaigns = $this->getCampaigns();
            return array_get($campaigns, $campaign_id, $this->campaignRepo->get($campaign_id, false)->load('rules', 'counter'));
        }

        return $campaign_id;
    }

    protected function refreshCampaigns()
    {
        $campaigns = $this->campaignRepo->getAll(true);
        $campaigns->load('rules', 'counter');

        Cache::forget(self::CACHE_KEY_OF_CAMPAIGNS);

        return Cache::remember(self::CACHE_KEY_OF_CAMPAIGNS, $this->getCacheTime($campaigns), function () use ($campaigns) {
            return $this->decodePromotions($campaigns);
        });
    }

    private function getCacheTime($promotions)
    {
        $recent_end_time = null;
        foreach ($promotions as $promotion) {
            if (is_null($recent_end_time) || strtotime($promotion['end_time']) < $recent_end_time) {
                $recent_end_time = strtotime($promotion['end_time']);
            }
        }
        return bcdiv($recent_end_time - time(), 60, 0);
    }


    public function getCampaignsByProduct($product_id, $sku_id, $cat_id, $group_id, $brand_id)
    {

    }

    public function getCouponsByProduct($product_id, $sku_id, $cat_id, $group_id, $brand_id)
    {

    }

    public function getCoupons($coupon_ids)
    {
        return $this->decodePromotions($this->couponRepo->getCouponsById($coupon_ids));
    }

    private function decodePromotions($promotion_models)
    {
        $promotions = [];
        foreach ($promotion_models as $promotion_model) {
            $promotions[$promotion_model['id']] = $this->decodePromotion($promotion_model);
        }
        return $promotions;
    }

    private function decodePromotion($promotion)
    {
        $data = [
            'id' => $promotion['id'],
            'name' => $promotion['name'],
            'desc' => $promotion['desc'],
            'start_time' => $promotion['start_time'],
            'end_time' => $promotion['end_time'],
            'active' => $promotion['active'],
            'remain' => 1
        ];
        if ($promotion instanceof Coupon) {
            $data['remain'] = $promotion['counter']['remain'];
        }

        $data['rules'] = $this->decodePromotionRules($promotion['rules']);
    }

    private function decodePromotionRules($rule_models)
    {
        $rules = [];
        foreach ($rule_models as $rule_model) {
            $rules[$rule_model['id']] = $this->decodePromotionRule($rule_model);
        }
        return $rules;
    }

    private function decodePromotionRule($rule)
    {
        return [
            'id' => $rule['id'],
            'qualify' => [
                'type' => $rule['qua_type'],
                'quantity' => $rule['qua_quantity'],
                'values' => to_array($rule['qua_content'])
            ],
            'items' => [
                'item_type' => $rule['item_type'],
                'values' => to_array($rule['item_content'])
            ],
            'range' => [
                'type' => $rule['range_type'],
                'min' => $rule['range_min'],
                'max' => $rule['range_max']
            ],
            'discount' => [
                'type' => $rule['discount_resource'],
                'mode' => $rule['discount_mode'],
                'value' => $rule['discount_content']
            ],
            'weight' => $rule['weight'],
            'multi' => $rule['multi_able']
        ];
    }

    public function getUserPromotionTimes($promotion_id, $user_id)
    {
        return UserPromotion::where('user_id', $user_id)->where('promotion_id', $promotion_id)->count();
    }
}
