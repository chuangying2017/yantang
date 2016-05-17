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
        return Attribute::where('id', $attr_id)->update(['name' => $name]);
    }

    public function getAllAttributes($with_value = false)
    {
        return $this->queryAttributes(null, $with_value);
    }

    public function deleteAttribute($attr_id)
    {
        $this->attributeValueRepository->deleteAttribute($attr_id);
        return Attribute::where('id', $attr_id)->delete();
    }

    public function getAttributesById($id, $with_value = true)
    {
        return $this->queryAttributes($id, $with_value);
    }

    protected function queryAttributes($id = null, $with_value = false, $fields = ['id', 'name'])
    {
        $query = Attribute::query();

        if ($with_value) {
            $query = $query->with('values')->select($fields);
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
