<?php namespace App\Models\Access\User\Traits\Attribute;

use Illuminate\Support\Facades\Hash;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait UserAttribute {

    /**
     * Hash the users password
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (!is_null($value))
            $this->attributes['password'] = bcrypt($value);
        else
            $this->attributes['password'] = $value;
    }
}
