<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16/016
 * Time: 17:40
 */

namespace App\Repositories\Common\updateOrSave;

use Illuminate\Database\Eloquent\Model;

abstract class CommonInsertMode
{
    public function save(Model $model, $array)
    {
        if (empty($array)) return false;

        $model->fill($array);

        $model->save();

        return $model;
    }
}