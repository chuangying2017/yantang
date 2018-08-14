<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/18/018
 * Time: 10:13
 */

namespace App\Repositories\setting;


use App\Models\Settings;


class SetLogic implements SetMode
{

    public function updateSet($setting_id, $settingData)
    {
        // TODO: Implement updateSet() method.
        $fetch = Settings::find($setting_id);
        return submitStatus($fetch->fill(['value'=>$this->filterData($settingData)])->save());
    }

    protected function filterData($data){
        return  array_only($data,[
            'interval_time',
            'star_five',
            'star_four',
            'star_one',
            'star_three',
            'star_two',
            'user_score',
        ]);
    }

    public function getSetting($setting_id){
        return Settings::find($setting_id,['id','value']);
    }

    public function update($id,$data)
    {
        $model = $this->getSetting($id);

        $model->fill(['value' => $data]);

        return $model->save();
    }

}