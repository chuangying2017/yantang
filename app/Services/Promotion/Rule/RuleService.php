<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class RuleService implements RuleServiceContract {

    /**
     * @var SpecifyRuleContract
     */
    protected $specifyRuleService;

    /**
     * @var RuleDataContract
     */
    private $rules;
    /**
     * @var PromotionAbleUserContract
     */
    private $user;

    /**
     * @var PromotionAbleItemContract
     */
    protected $items;

    /*
	* @var PromotionSupportRepositoryContract
	*/
    protected $promotionSupport;


    /**
     * RuleService constructor.
     * @param RuleDataContract $rules
     * @param SpecifyRuleContract $specifyRuleService
     */
    public function __construct(RuleDataContract $rules, SpecifyRuleContract $specifyRuleService)
    {
        $this->rules = $rules;
        $this->specifyRuleService = $specifyRuleService;
    }

    /**
     * @return $this
     */
    public function filterQualify()
    {
        $this->specifyRuleService->setUser($this->user);

        foreach ($this->rules->getAllKeys() as $rule_key) {
            $this->specifyRuleService
                ->setRules($this->rules, $rule_key)
                ->checkUserQualify();
        }

        return $this;
    }

    public function filterRelate()
    {
        foreach ($this->rules->getAllKeys() as $rule_key) {
            $this->setSpecifyRuleServiceInfo($rule_key);

            //检查并设置用户是否有参加活动资格,没有资格则删除规则
            if ($this->specifyRuleService->checkUserQualify()) {
                //检查商品是否符合规则要求,符合则标记为关联
                $this->specifyRuleService->checkRelateItems();
            }
        }

        return $this;
    }

    public function filterUsable()
    {
        /**
         * 规则排序:
         * 1. 排他优先
         * 2. 分组和组内权重降序排序（分组优先,权重大优先）
         * 3. 规则对象范围小优先
         */
        $this->rules->sortRules();

        /**
         * 计算生效的规则
         * 1. 计算优惠资源
         * 4. 记录未生效信息
         */
        foreach ($this->rules->getAllKeys() as $rule_key) {

            $this->setSpecifyRuleServiceInfo($rule_key);

            $this->specifyRuleService->checkItemsInRange();
        }

        return $this;
    }

    public function using($rule_key)
    {
        $this->setSpecifyRuleServiceInfo($rule_key);

        if (!$this->specifyRuleService->isUsable() || $this->specifyRuleService->isUsing()) {
            $this->rules->setMessage('优惠券不可用于该订单');
            return false;
        }

        /**
         * 1. 计算优惠资源
         * 2. 排他生效后结束
         * 3. 组内权重高生效后跳过同组
         */
        $this->specifyRuleService->setAsUsing();

        return true;
    }

    public function notUsing($rule_key)
    {
        $this->setSpecifyRuleServiceInfo($rule_key);

        if (!$this->specifyRuleService->isUsing()) {
            return $this;
        }

        $this->specifyRuleService->setAsNotUsing();

        return $this;
    }


    /**
     * @param null $rule_key
     * @return $this
     */
    protected function setSpecifyRuleServiceInfo($rule_key = null)
    {
        $this->specifyRuleService->setUser($this->user)->setItems($this->items);
        if (!is_null($rule_key)) {
            $this->specifyRuleService->setRules($this->rules, $rule_key);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules->getAll();
    }

    /**
     * @param PromotionAbleItemContract $items
     * @return mixed
     */
    public function setItems(PromotionAbleItemContract $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param PromotionAbleUserContract $user
     * @return $this
     */
    public function setUser(PromotionAbleUserContract $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param mixed $rules
     * @return RuleService
     */
    public function setRules(array $rules)
    {
        $this->rules->init($rules);
        return $this;
    }


}
