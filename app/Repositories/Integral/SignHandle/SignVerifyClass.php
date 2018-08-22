<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/19/019
 * Time: 0:01
 */

namespace App\Repositories\Integral\SignHandle;


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
            return $this->integralSuccess(verify_dataMessage($this->SaveData($data)),['integral' => $data['integral']]);
        }

        $this->verifyToDayExists($model);

        if (!empty($this->errorMessage))
        {
            return verify_dataMessage($this->errorMessage);
        }

        if ($this->verifyYesterdayExists($model))
        {
            $model->continuousSign += 1;
        }else{
            $model->continuousSign = 1;
        }
            $data = $this->updateData();

            $model->total += 1;

            array_push($model->signArray,Carbon::now()->day);

            $model->total_integral += $this->rewards;

            $this->model = $model;

        return $this->integralSuccess(verify_dataMessage($this->SaveData($data)),['integral' => $data['integral']]);
    }


    public function verifyToDayExists($SignModel)
    {
        $result = $this->verifyDay($SignModel,Carbon::now()->day);

        if ($result)
        {
            $this->errorMessage = '今天已签到过！';
        }

    }

    public function verifyYesterdayExists($SignModel)//判断是否为连续签到
    {
        $result = $this->verifyDay($SignModel,Carbon::now()->addDay(-1)->day);

        if ($result){
            return true;//是连续签到
        }
        return false;//is not continue sign
    }

    public function verifyDay($SignModel,$days)
    {
     return   $SignModel->withCount(['sign_integral_record' => function($query)use($days){
            $query->where('days','=',$days);
        }])->first()->sign_integral_record_count;
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
           return '不能大于当天或等于当天';
        }

        if (empty($model = $this->fetchMonthSign()))//如果月没有记录 添加月记录 有 继续查询 当天有无记录
        {
            return '补签失败请选择,签到';
        }

        if ($this->verifyDay($model,$day))
        {
            return '补签无效不能选择已签到';
        }

        $record = $model->sign_integral_record;

        if ($record->repairNum > 0)
        {
            return '每天只能补签一次';
        }

        $ret = $this-> signRule();
        if (!is_array($ret['retroactive']) && !in_array(SignClass::SIGN_RETROACTIVE,$ret['retroactive']))
        {
            return '补签通道关闭';
        }

        if ($ret['extend_rule']['compensateIntegral'] > $this->integralMember()['integral'])
        {
            return '积分不足';
        }

        $data = [
            'month' => ['signArray' => $model->signArray[] = $day,'total' => $model->total + 1],
            'record' => ['days' => $day,'everyday_integral' => -$ret['extend_rule']['compensateIntegral']],
        ];




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

        return $this->model->whereYear('created_at','=',$parse->year)->whereMonth('created_at','=',$parse->month)->with('sign_cte')->first();
    }

    protected function integralMember()
    {
        $integl = new IntegralMode();

        return $integl->memberIntegral($this->array['user_id']);
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
        $rule = $this->signRule();
    }
}