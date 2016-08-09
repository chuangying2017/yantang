<?php namespace App\Repositories\Promotion\Rule;

use App\Models\Promotion\Rule;
use App\Models\Promotion\RuleItem;
use App\Models\Promotion\RuleQualify;

class EloquentRuleRepository implements RuleRepositoryContract {


    public function createRule($qualifies, $items, $range, $discount, $weight, $multi, $memo)
    {
        return $rule = $this->updateOrCreate(null, $qualifies, $items, $range, $discount, $weight, $multi, $memo);
    }

    protected function updateOrCreate($rule_id = null, $qualifies, $items, $range, $discount, $weight, $multi, $memo)
    {
        $rule_data = [
            'qua_type' => $qualifies['type'],
            'qua_quantity' => array_get($qualifies, 'quantity', 1),
            'qua_content' => $qualifies['values'],
            'item_type' => $items['type'],
            'range_type' => $range['type'],
            'range_min' => $range['min'],
            'range_max' => array_get($range, 'max', null),
            'discount_resource' => $discount['type'],
            'discount_mode' => $discount['mode'],
            'discount_content' => $discount['value'],
            'weight' => $weight,
            'multi_type' => $multi,
            'memo' => $memo
        ];
        $rule = is_null($rule_id) ? new Rule() : $this->getRule($rule_id);
        $rule->fill($rule_data);
        $rule->save();
        $this->syncItems($rule['id'], $items);
        $this->syncQualifies($rule['id'], $qualifies);
        return $rule;
    }

    public function getRule($rule_id)
    {
        return $rule_id instanceof Rule ? $rule_id : Rule::find($rule_id);
    }

    public function updateRule($rule_id, $qualifies, $items, $range, $discount, $weight, $multi, $memo)
    {
        return $this->updateOrCreate($rule_id, $qualifies, $items, $range, $discount, $weight, $multi, $memo);
    }

    public function getRules($with_values = true)
    {
        return Rule::with('items', 'qualifies')->get();
    }

    public function syncItems($rule_id, $items)
    {
        RuleItem::where('rule_id', $rule_id)->delete();

        $item_data = [];
        foreach ($items['values'] as $value) {
            $item_data[] = ['value' => $value];
        }

        $rule = $this->getRule($rule_id);
        $rule->items()->createMany($item_data);
    }


    public function syncQualifies($rule_id, $qualifies)
    {
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
        Rule::find($rule_id)->delete();
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
