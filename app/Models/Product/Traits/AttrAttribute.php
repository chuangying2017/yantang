<?php namespace App\Models\Product\Traits;
trait AttrAttribute {

    public function setAttrAttribute($value)
    {
        if($value) {
            $this->attributes['attr'] = json_encode($value);
        } else {
            $this->attributes['attr'] = $value;
        }
    }

    public function getAttrAttribute()
    {
        return json_decode($this->attributes['attr']);
    }

}
