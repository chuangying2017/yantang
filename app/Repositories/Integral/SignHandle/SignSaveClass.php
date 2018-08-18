<?php
namespace App\Repositories\Integral\SignHandle;

use App\Models\Integral\SignMonthModel;
use App\Repositories\Common\updateOrSave\CommonInsertMode;

class SignSaveClass extends CommonInsertMode
{//注意此 不可随便添加公共方法 除非你非常熟悉该class用途

    public $data;

    public function signMonth(SignMonthModel $model)
    {
       $this->save($this->data['month'],$model);
    }

    public function signIntegralRecord(SignMonthModel $signMonthModel)
    {
        $this->save($this->data['record'],$signMonthModel->sign_integral_record());
    }

    public function signIntegralCte(SignMonthModel $signMonthModel)
    {
        $this->save($this->data['cte'],$signMonthModel->sign_cte());
    }

}