<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18/018
 * Time: 10:10
 */
namespace App\Repositories\setting;

interface SetMode
{
        public function updateSet($setting_id, $settingData);
}