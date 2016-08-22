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
        return $this;
    }

    public function setUser(PromotionAbleUserContract $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * 过滤相关
     * @param null $rules
     * @return $this
     */
    public function checkRelated($rules = null)
    {
        $rules_array = is_null($rules) ? $this->promotionRepo->getUsefulRules() : $rules;
        
        $this->ruleService->setRules($rules_array);
        $this->ruleService->setUser($this->user)->setItems($this->items);

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
        } else {
            $this->revert();
        }

        $this->ruleService->filterUsable();

        $this->updateItemsRules();

        return $this;
    }

    public function setUsing($rule_key)
    {
        if (!$this->items->getRules()) {
            $this->checkUsable();
        } else {
            $this->revert();
        }

        $success = $this->ruleService->using($rule_key);
        
        $this->updateItemsRules();

        return $success;
    }

    public function setNotUsing($rule_key = null)
    {
        if (!$this->items->getRules()) {
            $this->checkUsable();
        } else {
            $this->revert();
        }

        if (is_null($rule_key)) {
            $this->ruleService->notUsingAll();
        } else {
            $this->ruleService->notUsing($rule_key);
        }

        $this->updateItemsRules();

        return $this;
    }

    protected function updateItemsRules()
    {
        $this->items->setRules($this->ruleService->getRules());
    }

    public function getRules()
    {
        return $this->ruleService->getRules();
    }

    protected function revert()
    {
        $this->ruleService->setUser($this->user)->setItems($this->items)->setRules($this->items->getRules());
    }


}
