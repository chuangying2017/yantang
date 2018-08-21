<?php
namespace App\Repositories\Integral\SignHandle;

use App\Models\Client\Account\Wallet;
use App\Models\Integral\IntegralRecord;
use App\Models\Integral\SignMonthModel;
use App\Repositories\Common\updateOrSave\CommonInsertMode;
use Carbon\Carbon;
use Mockery\Exception;

class SignSaveClass extends CommonInsertMode
{//注意此 不可随便添加公共方法 除非你非常熟悉该class用途

    public $data;

    public $model;

    public function signMonth()
    {
        $this->model = $this->save($this->model,$this->data['month']);
    }

    public function signIntegralRecord()
    {
        $this->model->sign_integral_record()->create($this->data['record']);
    }

    public function sign_subsidiary()//明细
    {//首次签到 每日签到 连续签到 总签到 特殊奖励 扣除补签积分
        IntegralRecord::create(array_merge([
            'type_id' => $this->model->id,
            'record_able' => get_class($this->model),
            'user_id' => $this->data['user_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ],$this->data['integral_record']));
    }

    public function sign_member()//会员积分计算
    {
       $member = Wallet::query()->where('user_id','=',$this->data['user_id']);
        if ($this->data['member'] == 'increment')
        {
            $member->increment('integral',$this->data['integral']);
        }elseif ($this->data['member'] == 'decrement')
        {
            $member->decrement('integral',$this->data['integral']);
        }else
        {
            throw new Exception('没有该类型002',500);
        }
    }

    public function signIntegralCte()
    {
          if ($this->model->sign_cte)
          {
              $this->model->sign_cte->fill($this->data['cte'])->save();
          }else{
              $this->model->sign_cte()->create($this->data['cte']);
          }
    }

}