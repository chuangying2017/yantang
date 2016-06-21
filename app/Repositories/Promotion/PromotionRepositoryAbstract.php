<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\UserPromotion;
use App\Repositories\Promotion\Rule\RuleRepositoryContract;
use App\Services\Order\OrderProtocol;
use Carbon\Carbon;

abstract class PromotionRepositoryAbstract implements PromotionRepositoryContract, PromotionSupportRepositoryContract {

    protected $model;

    /**
     * @var RuleRepositoryContract
     */
    private $ruleRepo;

    /**
     * PromotionRepositoryAbstract constructor.
     * @param RuleRepositoryContract $ruleRepo
     */
    public function __construct(RuleRepositoryContract $ruleRepo)
    {
        $this->init();
        $this->ruleRepo = $ruleRepo;
    }

    protected abstract function init();

    protected abstract function attachRelation($promotion_id, $data);

    protected abstract function updateRelation($promotion_id, $data);

    public function create($data)
    {
        $promotion = $this->fillPromotion(null, $data);

        $this->syncRules($promotion, $data['rules']);

        $this->attachRelation($promotion['id'], $data);

        return $promotion;
    }

    private function fillPromotion($promotion_id = null, $data)
    {
        $model = $this->getModel();

        $promotion_data = [
            'name' => array_get($data, 'name'),
            'desc' => array_get($data, 'desc'),
            'start_time' => array_get($data, 'start_time', Carbon::now()),
            'end_time' => array_get($data, 'end_time', Carbon::today()->addYears(10)),
            'active' => array_get($data, 'active', 1),
        ];

        $promotion = is_null($promotion_id) ? new $model() : $this->get($promotion_id);
        $promotion->fill($promotion_data);
        $promotion->save();
        return $promotion;
    }

    private function syncRules($promotion, $rules, $create = true)
    {
        $rule_ids = [];
        foreach ($rules as $rule_data) {
            $rule = $this->ruleRepo->updateRule(
                $create ? null : array_get($rule_data, 'id', null),
                $rule_data['qualify'],
                $rule_data['items'],
                $rule_data['range'],
                $rule_data['discount'],
                $rule_data['weight'],
                $rule_data['multi'],
                $rule_data['memo']
            );
            $rule_ids[] = $rule['id'];
        }

        $promotion->rules()->sync($rule_ids);
    }


    public function getAll($not_over_time = true)
    {
        $model = $this->getModel();
        $query = $model::newQuery();
        if ($not_over_time) {
            $query = $query->effect();
        }
        return $query->get();
    }

    public function getAllPaginated($not_over_time = true)
    {
        $model = $this->getModel();
        $query = $model::newQuery();
        if ($not_over_time) {
            $query = $query->effect();
        }

        return $query->paginate(OrderProtocol::ORDER_PER_PAGE);
    }

    public abstract function get($promotion_id, $with_detail = true);

    public function update($promotion_id, $data)
    {
        $promotion = $this->fillPromotion($promotion_id, $data);
        $this->syncRules($promotion, $data['rules'], false);

        $this->updateRelation($promotion_id, $data);

        return $promotion;
    }

    public function delete($promotion_id)
    {
        $promotion = $this->get($promotion_id);
        $this->ruleRepo->deleteRuleOfPromotion($promotion_id);
        $promotion->delete();
        return 1;
    }

    /**
     * @param mixed $model
     * @return PromotionRepositoryAbstract
     */
    protected function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getModel()
    {
        return $this->model;
    }

    /*
     * Support
     */
    public function getUsefulRules()
    {
        $promotions = $this->getUsefulPromotions();

        $rules = [];
        foreach ($promotions as $promotion) {
            $promotion_rules = $this->decodePromotionRules($promotion);
            if ($promotion_rules) {
                $rules = array_merge($rules, $promotion_rules);
            }
        }

        return $rules;
    }

    public abstract function getUsefulPromotions();


    protected function decodePromotionRules($promotion)
    {
        if ($promotion['counter']['remain'] > 0) {
            foreach ($promotion['rules'] as $rule_model) {
                $this->decodePromotionRule($rule_model, $promotion);
            }
        }
        return false;
    }

    protected function decodePromotionRule($rule, $promotion)
    {
        return [
            'id' => $rule['id'],
            'promotion_id' => $promotion['id'],
            'promotion_type' => get_class($promotion),
            'group' => $promotion['id'],
            'qualify' => [
                'type' => $rule['qua_type'],
                'quantity' => $rule['qua_quantity'],
                'values' => to_array($rule['qua_content'])
            ],
            'items' => [
                'type' => $rule['item_type'],
                'values' => to_array($rule['item_content']),
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

    public function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null)
    {
        $query = UserPromotion::where('user_id', $user_id)->where('promotion_id', $promotion_id);

        if (!is_null($rule_id)) {
            $query = $query->where('rule_id', $rule_id);
        }

        return $query->count();
    }
}
