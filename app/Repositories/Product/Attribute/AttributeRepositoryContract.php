<?php namespace App\Repositories\Product\Attribute;

use App\Repositories\Product\ProductProtocol;

interface AttributeRepositoryContract {

    public function createAttribute($name, $merchant = ProductProtocol::DEFAULT_MERCHANT_ID);

    public function updateAttribute($attr_id, $name);

    public function getAllAttributes($with_value = false);

    public function getAttributesById($id, $with_value = true);

    public function deleteAttribute($attr_id);

}
