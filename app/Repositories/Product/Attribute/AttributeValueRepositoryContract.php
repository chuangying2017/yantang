<?php namespace App\Repositories\Product\Attribute;

interface AttributeValueRepositoryContract {

    public function getAllValuesOfAttributes($attr_id);

    public function getValues($value_id, $with_attr = true);

    public function createAttribute($attr_id, $name);

    public function deleteAttribute($attr_id);

    public function updateAttribute($attr_id, $name);

}
