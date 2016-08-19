<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\PromotionAbstract;
use App\Repositories\Promotion\Rule\RuleRepositoryContract;
use App\Services\Order\OrderProtocol;
use Carbon\Carbon;

abstract class PromotionRepositoryAbstract implements PromotionRepositoryContract {

    /**
     * @var PromotionAbstract
     */
    protected $model;

    /**
     * @var RuleRepositoryContract
     */
    protected $ruleRepo;


    /**
     * @var TicketRepositoryContract
     */
    protected $ticketRepo;

    /**
     * PromotionRepositoryAbstract constructor.
     * @param RuleRepositoryContract $ruleRepo
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(RuleRepositoryContract $ruleRepo, TicketRepositoryContract $ticketRepo)
    {
        $this->init();
        $this->ruleRepo = $ruleRepo;
        $this->ticketRepo = $ticketRepo;
    }

    protected abstract function init();

    protected abstract function attachRelation($promotion_id, $data);

    protected abstract function updateRelation($promotion_id, $data);

    public function create($data)
    {
        $promotion = $this->fillPromotion(null, $data);

        if (isset($data['rules'])) {
            $this->syncRules($promotion, $data['rules']);
        }

        $this->attachRelation($promotion['id'], $data);

        return $promotion;
    }

    private function fillPromotion($promotion_id = null, $data)
    {
        $model = $this->getModel();

        $promotion_data = [
            'name' => array_get($data, 'name'),
            'desc' => array_get($data, 'desc', ''),
            'cover_image' => array_get($data, 'cover_image', ''),
            'start_time' => array_get($data, 'start_time', Carbon::now()),
            'end_time' => array_get($data, 'end_time', Carbon::today()->addYears(10)),
            'active' => array_get($data, 'active', 1),
        ];

        $promotion = is_null($promotion_id) ? new $model : $this->get($promotion_id);
        $promotion->fill($promotion_data);
        $promotion->save();
        return $promotion;
    }

    private function syncRules(PromotionAbstract $promotion, $rules, $create = true)
    {
        $rule_ids = [];
        foreach ($rules as $rule_data) {
            $rule = $this->ruleRepo->updateRule(
                $create ? null : array_get($rule_data, 'id', null),
                $rule_data['name'],
                $rule_data['desc'],
                $rule_data['qualify'],
                $rule_data['items'],
                $rule_data['range'],
                $rule_data['discount'],
                $rule_data['weight'],
                $rule_data['multi']
            );
            $rule_ids[] = $rule['id'];
        }

        $promotion->rules()->sync($rule_ids);

    }


    public function getAll($not_over_time = true)
    {
        $query = $this->getQuery();
        if ($not_over_time) {
            $query = $query->effect();
        }

        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function getAllPaginated($not_over_time = true)
    {
        $query = $this->getQuery();
        if ($not_over_time) {
            $query->effect();
        }

        return $query->paginate(OrderProtocol::ORDER_PER_PAGE);
    }

    /**
     * @param $promotion_id
     * @param bool $with_detail
     * @return PromotionAbstract
     */
    public abstract function get($promotion_id, $with_detail = true);

    public function update($promotion_id, $data)
    {
        $promotion = $this->fillPromotion($promotion_id, $data);

        if (isset($data['rules'])) {
            $this->syncRules($promotion, $data['rules'], false);
        }

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
     * @param string $model
     * @return PromotionRepositoryAbstract
     */
    protected function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return PromotionAbstract
     */
    protected function getModel()
    {
        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery()
    {
        $model = $this->getModel();
        return $model::query();
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

    public function getPromotionWithDecodeRules($promotion_id)
    {
        $promotion = $this->get($promotion_id, true);

        $promotion['rules'] = $this->decodePromotionRules($promotion);

        return $promotion;
    }

    protected function decodePromotionRules($promotion)
    {
        if ($promotion['counter']['remain'] > 0) {
            $rules = [];
            foreach ($promotion['rules'] as $rule_model) {
                $rules[] = $this->decodePromotionRule($rule_model, $promotion);
            }
            return $rules;
        }
        return [];
    }

    protected function decodePromotionRule($rule, $promotion)
    {
        return [
            'id' => $rule['id'],
            'name' => $rule['name'],
            'desc' => $rule['desc'],
            'promotion' => [
                'id' => $promotion['id'],
                'name' => $promotion['name'],
                'desc' => $promotion['desc'],
                'cover_image' => $promotion['cover_image'],
                'start_time' => $promotion['start_time'],
                'end_time' => $promotion['end_time'],
                'type' => $promotion['type']
            ],
            'ticket' => array_get($promotion, 'ticket'),
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
                'value' => json_decode($rule['discount_content'])
            ],
            'weight' => $rule['weight'],
            'multi' => $rule['multi_able'],
        ];
    }



}
