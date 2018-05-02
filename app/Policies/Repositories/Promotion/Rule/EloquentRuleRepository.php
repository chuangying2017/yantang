<?php namespace App\Repositories\Promotion\Rule;

use App\Models\Promotion\Rule;
use App\Models\Promotion\RuleItem;
use App\Models\Promotion\RuleQualify;
use App\Services\Promotion\PromotionProtocol;

class EloquentRuleRepository implements RuleRepositoryContract {


    public function createRule($name, $desc, $qualifies, $items, $range, $discount, $weight, $multi)
    {
        return $rule = $this->updateOrCreate(null, $name, $desc, $qualifies, $items, $range, $discount, $weight, $multi);
    }

    protected function updateOrCreate($rule_id = null, $name, $desc, $qualifies, $items, $range, $discount, $weight, $multi)
    {
        $rule_data = [
            'name' => $name,
            'desc' => $desc,
            'qua_type' => $qualifies['type'],
            'qua_quantity' => array_get($qualifies, 'quantity', 1),
            'qua_content' => json_encode($qualifies['values']),
            'item_type' => $items['type'],
            'item_content' => json_encode($items['values']),
            'range_type' => $range['type'],
            'range_min' => PromotionProtocol::checkRangeContentIsPrice($range['type']) ?
                store_price($range['min']) :
                $range['min'],
            'range_max' => PromotionProtocol::checkRangeContentIsPrice($range['type']) ?
                store_price(array_get($range, 'max', null)) :
                array_get($range, 'max', null),
            'discount_resource' => $discount['type'],
            'discount_mode' => $discount['mode'],
            'discount_content' => $this->transDiscountContent($discount),
            'weight' => $weight,
            'multi_able' => $multi,
        ];
        $rule = is_null($rule_id) ? new Rule() : $this->getRule($rule_id);
        $rule->fill($rule_data);
        $rule->save();
        $this->syncItems($rule['id'], $items);
        $this->syncQualifies($rule['id'], $qualifies);
        return $rule;
    }

    protected function transDiscountContent($discount)
    {
        $content = json_encode($discount['value']);
        if (PromotionProtocol::discountContentIsAmount($discount['type'], $discount['mode'])) {
            return store_price($content);
        } else if (PromotionProtocol::discountContentIsPercentage($discount['mode'])) {
            return store_percentage($content);
        }

        return $content;
    }

    /**
     * @param $rule_id
     * @return Rule
     */
    public function getRule($rule_id)
    {
        return $rule_id instanceof Rule ? $rule_id : Rule::query()->find($rule_id);
    }

    public function updateRule($rule_id, $name, $desc, $qualifies, $items, $range, $discount, $weight, $multi)
    {
        return $this->updateOrCreate($rule_id, $name, $desc, $qualifies, $items, $range, $discount, $weight, $multi);
    }

    public function getRules($with_values = true)
    {
        return Rule::with('items', 'qualifies')->get();
    }

    public function syncItems($rule_id, $items)
    {
        RuleItem::query()->where('rule_id', $rule_id)->delete();

        $item_data = [];
        foreach (to_array($items['values']) as $value) {
            $item_data[] = ['value' => $value];
        }

        $rule = $this->getRule($rule_id);
        $rule->items()->createMany($item_data);
    }


    public function syncQualifies($rule_id, $qualifies)
    {
        if (empty($qualifies['values']) || !$qualifies['values']) {
            return 0;
        }

        RuleQualify::query()->where('rule_id', $rule_id)->delete();

        $qua_data = [];
        foreach ($qualifies['values'] as $value) {
            $qua_data[] = ['value' => $value, 'count' => $qualifies['quantity']];
        }

        $rule = $this->getRule($rule_id);
        $rule->qualifies()->createMany($qua_data);
    }

    public function deleteRule($rule_id)
    {
        RuleItem::query()->where('rule_id', $rule_id)->delete();
        RuleQualify::query()->where('rule_id', $rule_id)->delete();
        Rule::query()->find($rule_id)->delete();
        return 1;
    }

    public function deleteRuleOfPromotion($promotion_id)
    {
        $rule_ids = \DB::table('promotion_rule')->where('promotion_id', $promotion_id)->pluck('rule_id');
        RuleItem::whereIn('rule_id', $rule_ids)->delete();
        RuleQualify::whereIn('rule_id', $rule_ids)->delete();
        Rule::whereIn('id', $rule_ids)->delete();
        return 1;
    }
}
