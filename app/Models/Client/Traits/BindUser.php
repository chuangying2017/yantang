<?php namespace App\Models\Client\Traits;

trait BindUser {

    public function newQuery()
    {
        return parent::newQuery()->where('user_id', access()->id());
    }

    public function setUserIdAttribute($user_id = null)
    {
        $this->attributes['user_id'] = is_null($user_id) ? access()->id() : $user_id;
    }

}
