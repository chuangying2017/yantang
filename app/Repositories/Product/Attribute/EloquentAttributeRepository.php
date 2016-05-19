<?php namespace App\Repositories\Product\Attribute;

use App\Models\Product\Attribute;
use App\Repositories\Product\ProductProtocol;

class EloquentAttributeRepository implements AttributeRepositoryContract {

    /**
     * @var AttributeValueRepositoryContract
     */
    private $attributeValueRepository;

    /**
     * EloquentAttributeRepository constructor.
     * @param AttributeValueRepositoryContract $attributeValueRepository
     */
    public function __construct(AttributeValueRepositoryContract $attributeValueRepository)
    {
        $this->attributeValueRepository = $attributeValueRepository;
    }

    public function createAttribute($name, $merchant = ProductProtocol::DEFAULT_MERCHANT_ID)
    {
        return Attribute::create(['name' => $name, 'merchant_id' => $merchant]);
    }

    public function updateAttribute($attr_id, $name)
    {
        $attr = Attribute::find($attr_id);
        $attr->name = $name;
        $attr->save();
        return $attr;
    }

    public function getAllAttributes($with_value = false)
    {
        $attr = Attribute::get();

        if ($with_value) {
            $attr->load('values');
        }

        return $attr;
    }

    public function getAttribute($attr_id, $with_value = true)
    {
        $attr = Attribute::find($attr_id);

        if ($with_value) {
            $attr->load('values');
        }

        return $attr;
    }

    public function deleteAttribute($attr_id)
    {
        $this->attributeValueRepository->deleteAttribute($attr_id);
        return Attribute::where('id', $attr_id)->delete();
    }

    public function getAttributesById($id, $with_value = true)
    {
        $attr = Attribute::find($id);

        if ($with_value) {
            $attr->load('values');
        }

        return $attr;
    }


}
