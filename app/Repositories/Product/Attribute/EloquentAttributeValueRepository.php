<?php namespace App\Repositories\Product\Attribute;

use App\Models\Product\AttributeValue;

class EloquentAttributeValueRepository implements AttributeValueRepositoryContract {


    protected $fields = ['id', 'name', 'attr_id'];

    public function getAllValuesOfAttributes($attr_id)
    {
        return AttributeValue::where('attr_id', $attr_id)->get($this->fields);
    }

    public function createAttribute($attr_id, $name)
    {
        return AttributeValue::create(compact('attr_id', 'name'));
    }

    public function deleteAttribute($attr_id)
    {
        return AttributeValue::where('attr_id', $attr_id)->delete();
    }

    public function updateAttribute($value_id, $name)
    {
        return AttributeValue::where('id', $value_id)->update(['name' => $name]);
    }

    public function getValues($value_id, $with_attr = true)
    {
        $values = AttributeValue::find($value_id, $this->fields);
        if ($with_attr) {
            $values->load('attr');
        }

        return $values;
    }

}
