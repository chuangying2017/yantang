<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/19/019
 * Time: 0:01
 */

namespace App\Repositories\Integral\SignHandle;


use App\Models\Client\Account\Wallet;
use App\Models\Integral\SignMonthModel;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use App\Repositories\Integral\SignRule\SignClass;
use App\Repositories\Integral\IntegralMode\IntegralMode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class SignVerifyClass extends ShareAccessRepositories
{
    protected $signSave;

    protected $errorMessage = '';

    protected $signClass;

    protected $rewards;
    public function __construct(SignSaveClass $signSaveClass,SignClass $signClass)
    {
        parent::__construct();
        $this->signSave = $signSaveClass;
        $this->signClass = $signClass;
        $this->rewards = $this->signIfFirst();
    }

    protected function init()
    {
       $this->set_model(new SignMonthModel);

       $this->set_UserInfo();
    }

    protected function set_UserInfo()
    {
        $this->array['user_id'] = access()->id();
    }

    protected function integralSuccess($array,$integral = [])
    {
        return $array[key($integral)] = array_values($integral);
    }

    public function verifyUserToday()//签到
    {
        if (empty($model = $this->fetchMonthSign()))//如果月没有记录 添加月记录 有 继续查询 当天有无记录
        {
            $data = $this->FirstData();
            $resutl = verify_dataMessage($this->SaveData($data));
            return $resutl['integral'] = $data['integral'];
        }

        $this->verifyToDayExists($model);

        if (!empty($this->errorMessage))
        {
            return verify_dataMessage($this->errorMessage);
        }

        $model->total += 1;

        $array = $model->signArray;

        array_push($array,Carbon::now()->day);

        $model->signArray = $array;

        if ($this->verifyYesterdayExists($model))
        {
            $this->countSign($model);
        }else{
            $model->continuousSign = 1;
        }
            $data = $this->updateData();

            $model->total_integral += $this->rewards;

            $this->model = $model;

            $r = verify_dataMessage($this->SaveData($data));
            if ($r['status'] == 1)
            {
                $r['integral'] = $data['integral'];
            }
        return $r;
    }


    public function verifyToDayExists($SignModel)
    {

        if (in_array(Carbon::now()->day,$SignModel->signArray))
        {
            $this->errorMessage = '今天已签到过！';
        }

    }

    public function verifyYesterdayExists($SignModel)//判断是否为连续签到
    {

        if (in_array(Carbon::now()->addDay(-1)->day,$SignModel->signArray)){
            return true;//是连续签到
        }
        return false;//is not continue sign
    }

    public function verifyDay($SignModel,$days)
    {

     return   $SignModel->with(['sign_integral_record' => function($query)use($days){
            $query->where('days','=',$days);
        }])->first()->sign_integral_record->count();
    }

    public function fetchMonthSign()
    {
        return $this->model->where('user_id','=',$this->array['user_id'])->whereYear('created_at','=',Carbon::now()->year)->whereMonth('created_at','=',Carbon::now()->month)->first();
    }

    protected function FirstData()
    {
        $rule = $this->signRule()['extend_rule'];

        return [
            'month' => [
                'user_id' => $this->array['user_id'],
                'seasons' => SignClass::fetchSeasons(),
                'total'=> '1',
                'continuousSign' => '1',
                'total_integral' => $this->rewards ?: 1,
                'monthDaysNum' => Carbon::now()->daysInMonth,
                'signArray' => [Carbon::now()->day]
            ],
            'record'=> ['days' => Carbon::now()->day,'everyday_integral' => $this->rewards ?: 1],
            'cte' => ['sign_integral' =>
                [
                    'continue7day'=>['status' => $rule['continuousOne']['status'] > 0 ? SignClass::SIGN_CONVERT_STATUS_ZERO : SignClass::SIGN_CONVERT_STATUS_THREE,'days'=>$rule['continuousOne']['days'],'integral'=>$rule['continuousOne']['rewards']],
                    'continue14day'=>['status' => $rule['continuousTwo']['status'] > 0 ? SignClass::SIGN_CONVERT_STATUS_ZERO : SignClass::SIGN_CONVERT_STATUS_THREE,'days'=>$rule['continuousTwo']['days'],'integral'=>$rule['continuousTwo']['rewards']],
                    'continue21day'=>['status'=> $rule['continuousThree']['status'] > 0 ? SignClass::SIGN_CONVERT_STATUS_ZERO : SignClass::SIGN_CONVERT_STATUS_THREE,'days'=>$rule['continuousThree']['days'],'integral'=>$rule['continuousThree']['rewards']]]
            ],//continue sign
            'integral_record' => ['integral' => '+' . $this->rewards ?: 1,'name' => SignClass::$signMode[$this->array['RewardMode']]],
            'member' => 'increment',
            'integral'=>$this->rewards,
            'user_id' => $this->array['user_id']
        ];
    }

    protected function signIfFirst()
    {
        $res = $this->model->where('user_id','=',$this->array['user_id'])->first();

        $get = $this->signClass->setFile(config('services.localStorageFile.SignRule'))->setPath(config('services.localStorageFile.path'))->get();
        $status = $get['extend_rule']['firstRewards'];
        if ($res)
        {
            $this->array[SignClass::SIGN_NORMAL_REWARD] = $status['everyday'];

            $this->array['RewardMode'] = SignClass::SIGN_NORMAL_REWARD;

            return $status['everyday']; //正常奖励
        }else
        {
            $reward = $status['status'] == 1 ? $status['rewards'] : $status['everyday'];

            $this->array['RewardMode'] = SignClass::SIGN_FIRST_REWARD;

            $this->array[SignClass::SIGN_FIRST_REWARD] = $reward;

            return $reward; //首次奖励
        }
    }

    public function SaveData($data)
    {
        try{
            \DB::beginTransaction();

            $this->signSave->data = $data;

            $this->signSave->model = $this->model;

            array_reduce(get_class_methods($this->signSave),function($v1,$v2)
            {
                $this->signSave->{$v2}();
            });
            \DB::commit();
            return true;
        }catch (Exception $exception)
        {
            \DB::rollBack();
            Log::error($exception->getMessage());
        }
            return 'fail';
    }

    public function RepairSign($day)
    {
        if ($day >= Carbon::now()->day)
        {
           return verify_dataMessage('不能大于当天或等于当天');
        }

        if (empty($model = $this->fetchMonthSign()))//如果月没有记录 添加月记录 有 继续查询 当天有无记录
        {
            return verify_dataMessage('补签失败请选择,签到');
        }

        if (in_array($day,$model->signArray))
        {
            return verify_dataMessage('补签无效');
        }

        $record = $model->sign_integral_record->where('days',Carbon::now()->day)->first();

        if ($record->repairNum > 0)
        {
            return verify_dataMessage('每天只能补签一次');
        }

        $ret = $this-> signRule();
        if (!in_array(SignClass::SIGN_RETROACTIVE,$ret['retroactive']))
        {
            return verify_dataMessage('补签通道关闭');
        }

        if ($ret['extend_rule']['compensateIntegral'] > $this->integralMember()->integral)
        {
            return verify_dataMessage('积分不够扣除');
        }

        $arr = $model->signArray;

        $arr[] = (int)$day;

        sort($arr);

        $model->signArray = $arr;

        $model->total += 1;

        $model->repairNum += 1;

        $data = [
            'record' => ['days' => $day,'everyday_integral' => -$ret['extend_rule']['compensateIntegral']],
            'integral_record' => ['name'=>SignClass::$signMode[SignClass::SIGN_INTEGRAL_REPAIR],'integral'=>'-' . $ret['extend_rule']['compensateIntegral']],
            'user_id' => $this->array['user_id'],
            'member'=>'decrement',
            'integral'=>$ret['extend_rule']['compensateIntegral']
        ];

        $this->countSign($model);

        $this->model = $model;

        $result = verify_dataMessage($this->SaveData($data));

        if ($result['status'] == 1)
        {
            $result['integral'] = '扣除积分'.$ret['extend_rule']['compensateIntegral'].'成功!';

            $record->repairNum += 1;

            $record->save();
        }

        return $result;

    }

    public function signRule()
    {
        return $this->signClass->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->get();
    }

    public function fetchSignMonth($date)
    {
        $parse = Carbon::parse($date);

        return $this->model->where('user_id','=',access()->id())->whereYear('created_at','=',$parse->year)->whereMonth('created_at','=',$parse->month)->with('sign_cte')->first();
    }

    protected function integralMember()
    {
      return  Wallet::query()->where('user_id','=',$this->array['user_id'])->firstOrFail();
    }

    protected function updateData($data = [])
    {
         $data['record']= ['days' => Carbon::now()->day,'everyday_integral' => $this->rewards ?: 1];
         $data['integral_record'] = ['integral' => '+' . $this->rewards ?: 1,'name' => SignClass::$signMode[$this->array['RewardMode']]];
         $data['member'] = 'increment';
         $data['integral']=$this->rewards;
         $data['user_id'] = $this->array['user_id'];
         return $data;
    }

    protected function countSign($model)//month model
    {
        $k = 1;

        $c = 1; //终结连续签到

        $arr = $model->signArray;

        $count = count($arr);

        if ($count < 2)
        {
            return false;
        }

        $cte = $model->sign_cte->sign_integral;

        for ($i=$count;$i > 1;$i--)
        {

            $j = $i - 1;

            $top = $arr[$j - 1] + 1;
            if ($top == $arr[$j]){
                $k += 1;
                if ((!isset($arr[$j - 2]) && $c == 1) || ($arr[$j - 2] + 1 != $arr[$j-1] && $c == 1))
                {
                    $c = 0;
                    $model->continuousSign = $k;
                }
            }else
            {
                $k = 1;
            }

            if ($cte['continue7day']['status'] == 0 && $k >= $cte['continue7day']['days'] && $k < $cte['continue14day']['days'])
            {
                $cte['continue7day']['status'] = 1;//待领取
                continue;
            }

            if ($cte['continue14day']['status'] == 0 && $k >= $cte['continue14day']['days'] && $k < $cte['continue21day']['days'])
            {
                $cte['continue21day']['status'] = 1;//待领取
                continue;
            }

            if ($cte['continue21day']['status'] == 0 && $k >= $cte['continue21day']['days'])
            {
                $cte['continue21day']['status'] = 1;//待领取
                break;
            }
        }
        $model->sign_cte->sign_integral = $cte;
    }

    public function GetIntegralContinue($day)
    {
        if (!$model = $this->fetchMonthSign())
        {
            return verify_dataMessage('当月无签到');
        }

        $integral = $model->sign_cte->sign_integral;

        if ($integral['continue7day']['status'] == 1 && $integral['continue7day']['days'] == $day)
        {
            $integral['continue7day']['status'] = 2;

            $model->total_integral += $integral['continue7day']['integral'];

            $data = $this->continue_data($integral['continue7day']['integral'],$integral['continue7day']['days']);

           return $this->fetchSave($model,$data,$integral);
        }

        if ($integral['continue14day']['status'] == 1 && $integral['continue14day']['days'] == $day)
        {
            $integral['continue14day']['status'] = 2;

            $model->total_integral += $integral['continue14day']['integral'];

            $data = $this->continue_data($integral['continue14day']['integral'],$integral['continue14day']['days']);

            return $this->fetchSave($model,$data,$integral);
        }

        if ($integral['continue21day']['status'] == 1 && $integral['continue21day']['days'] == $day)
        {
            $integral['continue21day']['status'] = 2;

            $model->total_integral += $integral['continue21day']['integral'];

            $data = $this->continue_data($integral['continue21day']['integral'],$integral['continue21day']['days']);

            return $this->fetchSave($model,$data,$integral);
        }

    }

    public function fetchSave($model,$data,$integral)
    {
        $model->fetchNum += 1;
        $model->sign_cte->sign_integral = $integral;
        $this->model = $model;
        $result = $this->SaveData($data);

        $res = verify_dataMessage($result);

        if ($res['status'] == 1)
        {
            $res['integral'] = $data['integral'];
        }

        return $res;
    }

    public function continue_data($integral,$day)
    {
        $data = [
            'integral_record' => ['integral' => '+'.$integral,'name'=>SignClass::$signMode[SignClass::CONTINUE_SIGN_DAYS] . $day . SignClass::FETCH_SIGN_INTEGRAL],
            'member' => 'increment',
            'integral' => $integral,
            'user_id' => $this->array['user_id']
        ];

        return $data;
    }
}