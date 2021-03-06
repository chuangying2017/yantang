<?php
namespace App\Repositories\Integral\Exchange;

use App\Models\Client\Account\Wallet;
use App\Models\Integral\IntegralConvertCoupon;
use App\Models\Integral\IntegralRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConvertRule extends ConvertInsert
{
    protected $errorMessage;

    public $function = ['DateVerify','remainNum','verifyRecord','verifyAmount'];

    public $updateFunction = ['createTicket','counter_convert','integral_record'];

    public function set_model(Model $model)
    {
        $this->model = $model;
    }

    public function get_model()
    {
        return $this->model;
    }

    public function set_VerifyData($data)
    {
        $this->VerifyData = $data;
    }

    public function DateVerify()
    {
        if (!date_between(Carbon::now()->toDateTimeString(),[$this->model['valid_time'],$this->model['deadline_time']]))
        {
            return '不在兑换时间范围内';
        }
        return true;
    }

    public function remainNum()
    {
        if ($this->model['remain_num'] <= 0)
        {
            return '卷已兑换完';
        }
        return true;
    }

    public function verifyRecord()//验证兑换记录
    {
            $record = IntegralRecord::where('type_id','=',$this->model->id)
                ->where('record_able','=',IntegralConvertCoupon::class)
                ->where('user_id','=',$this->VerifyData['user_id'])
                ->whereBetween('created_at',[$this->model['valid_time'],$this->model['deadline_time']])
                ->count();
            if ($record >= $this->model->limit_num)
            {
                return '兑换数量过多';
            }
            return true;
    }

    public function verifyAmount()//验证用户金额是否足够
    {
        $userAmount = $this->User->where('integral','>=',$this->model['cost_integral'])->count();
        if (!$userAmount)
        {
            return '积分不足';
        }

        return true;
    }

    public function set_user()
    {
        $this->User = Wallet::where('user_id','=',$this->VerifyData['user_id']);
    }
}