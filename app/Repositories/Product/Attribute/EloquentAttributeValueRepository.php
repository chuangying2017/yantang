<?php namespace App\Repositories\Product\Attribute;

use App\Models\Product\AttributeValue;

class EloquentAttributeValueRepository implements AttributeValueRepositoryContract {


    public function getAllValuesOfAttributes($attr_id)
    {
        return AttributeValue::where('attr_id', $attr_id)->get(['id', 'name']);
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
        return $this->queryValues($value_id, $with_attr);
    }

    protected function queryValues($id = null, $with_attr = false, $fields = ['id', 'name'])
    {
        $query = AttributeValue::query();

        if ($with_attr) {
            $query = $query->with('attr')->select($fields);
        }

        if (is_array($id)) {
            $query = $query->whereIn('id', $id);
        } else if (is_numeric($id)) {
            $query = $query->where('id', $id);
        } elseif (!is_null($id)) {
            return null;
        }

        return $query->get();
    }
}
