<?php namespace App\Models\Client\Traits;

trait BindUser {

    public function newQuery()
    {
        return parent::newQuery()->where('user_id', access()->id());
    }


}
