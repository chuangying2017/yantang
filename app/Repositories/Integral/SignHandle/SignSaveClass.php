<?php
namespace App\Repositories\Integral\SignHandle;

use App\Models\Integral\SignMonthModel;
use App\Repositories\Common\updateOrSave\CommonInsertMode;

class SignSaveClass extends CommonInsertMode
{//注意此 不可随便添加公共方法 除非你非常熟悉该class用途

    public $data;

    public function signMonth(SignMonthModel $model)
    {
       $this->save($model,$this->data['month']);
    }

    public function signIntegralRecord(SignMonthModel $signMonthModel)
    {
        $signMonthModel->sign_integral_record()->create($this->data['record']);
    }

    public function sign_subsidiary()//明细
    {//首次签到 每日签到 连续签到 总签到 特殊奖励

    }

    public function signIntegralCte(SignMonthModel $signMonthModel)
    {
          if ($signMonthModel->sign_cte)
          {
              $signMonthModel->sign_cte->fill($this->data['cte'])->save();
          }else{
              $signMonthModel->sign_cte()->create($this->data['cte']);
          }
    }

}