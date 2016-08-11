<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use App\Services\Traits\Messages;

abstract class PromotionServiceAbstract implements PromotionServiceContract {

    use Messages;

    /**
     * @var RuleServiceContract
     */
    protected $ruleService;
    /**
     * @var PromotionRepositoryContract
     */
    protected $promotionRepo;

    /**
     * @var PromotionAbleItemContract
     */
    protected $items;

    /**
     * @var PromotionAbleUserContract
     */
    protected $user;


    /**
     * PromotionServiceAbstract constructor.
     * @param PromotionRepositoryContract $promotionRepo
     * @param RuleServiceContract $ruleService
     */
    public function __construct(PromotionRepositoryContract $promotionRepo, RuleServiceContract $ruleService)
    {
        $this->ruleService = $ruleService;
        $this->promotionRepo = $promotionRepo;
    }

    public function setItems(PromotionAbleItemContract $items)
    {
        $this->items = $items;
    }

    public function setUser(PromotionAbleUserContract $user)
    {
        $this->user = $user;
    }

    /**
     * 过滤相关
     * @param null $rules
     * @return $this
     */
    public function checkRelated($rules = null)
    {
        $rules_array = is_null($rules) ? $this->promotionRepo->getUsefulRules() : $rules;

        $this->ruleService->setRules($rules_array)->setUser($this->user)->setItems($this->items);

        $this->ruleService->filterRelate();

        $this->updateItemsRules();

        return $this;
    }

    /**
     *
     * @return $this
     */
    public function checkUsable()
    {
        if (!$this->items->getRules()) {
            $this->checkRelated();
        }

        $this->ruleService->filterUsable();

        $this->updateItemsRules();

        return $this;
    }

    public function setUsing($rule_key)
    {
        if (!$this->items->getRules()) {
            $this->checkUsable();
        }

        $success = $this->ruleService->using($rule_key);

        $this->updateItemsRules();

        return $success;
    }

    public function setNotUsing($rule_key)
    {
        if (!$this->items->getRules()) {
            $this->checkUsable();
        }

        $this->ruleService->notUsing($rule_key);

        $this->updateItemsRules();

        return $this;
    }

    protected function updateItemsRules()
    {
        $this->items->setRules($this->ruleService->getRules());
    }

}
