<?php namespace App\Repositories\Promotion;

use App\Repositories\Promotion\Rule\RuleRepositoryContract;
use Carbon\Carbon;

abstract class PromotionRepositoryAbstract implements PromotionRepositoryContract {

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
        $query = $model::query();
        if ($not_over_time) {
            $query = $model::where(function ($query) {
                $query->where('start_time', '<', Carbon::now())
                    ->andWhere('end_time', '>', Carbon::now());
            });
        }
        return $query->get();
    }

    public function get($promotion_id)
    {
        $model = $this->getModel();
        return $promotion_id instanceof $model ? $promotion_id : $model::find($promotion_id);
    }

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


}
