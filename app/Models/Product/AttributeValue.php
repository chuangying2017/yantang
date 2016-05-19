<?php

namespace App\Models\Product;

use App\Models\Product\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'attribute_values';

    public function attr()
    {
        return $this->belongsTo(Attribute::class, 'attr_id', 'id');
    }

}
