<?php

namespace App\Models\Product;

use App\Models\Product\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function attr()
    {
        return $this->belongsTo(Attribute::class);
    }

}
