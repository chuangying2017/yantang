<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Data\PromotionData;

abstract class PromotionServiceAbstract {

    /**
     * @var PromotionData
     */
    private $data;
    /**
     * @var PromotionSupportRepositoryContract
     */
    private $promotionSupportRepo;

    /**
     * PromotionServiceAbstract constructor.
     * @param
     */
    public function __construct(PromotionData $data, PromotionSupportRepositoryContract $promotionSupportRepo)
    {
        $this->data = $data;
        $this->promotionSupportRepo = $promotionSupportRepo;
    }

    public function check($user, $items)
    {
        $rules = $this->promotionSupportRepo->getCampaignRules();

        $this->data->initPromotionData($user, $items, $rules);
        //检查用户资格

        //检查规则对象,关联规则

        /**
         * 规则排序:
         * 1. 排他优先
         * 2. 分组和组内权重降序排序（分组优先,权重大优先）
         * 3. 规则对象范围小优先
         */

        /**
         * 计算生效的规则
         * 1. 计算优惠资源
         * 2. 排他生效后结束
         * 3. 组内权重高生效后跳过同组
         * 4. 记录未生效信息
         */

        /**
         * 现有基础上增减优惠
         * 1. 检查和现有生效规则是否冲突
         * 2. 计算优惠资源
         */

        return $this->data->getPromotionData();
    }


}
