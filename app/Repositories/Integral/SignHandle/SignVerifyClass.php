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
use Carbon\Carbon;
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

    public function verifyUserToday()//签到
    {
        if (empty($model = $this->fetchMonthSign()))//如果月没有记录 添加月记录 有 继续查询 当天有无记录
        {
          return $this->SaveData($this->FirstData());
        }

        $this->verifyToDayExists($model);

        if (is_string($this->errorMessage))
        {
            return $this->errorMessage;
        }

        $data = $this->FirstData();

        if ($this->verifyYesterdayExists($model))
        {
            $data['month'] = [
                'continuousSign' => $model->continuousSign + 1
            ];
        }
            $data['month']['total'] = $model->total + 1;

            $data['month']['total_integral'] = $model->total_integral + $this->rewards;

            $this->model = $model;

        return $this->SaveData($data);
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
        }]);
    }

    public function fetchMonthSign()
    {
        return $this->model->where('user_id','=',$this->array['user_id'])->whereYear('created_at','=',Carbon::now()->year)->whereMonth('created_at','=',Carbon::now()->month)->first();
    }

    protected function FirstData()
    {
        return [
            'month' => [
                'user_id' => $this->array['user_id'],
                'seasons' => SignClass::fetchSeasons(),
                'total'=> '1',
                'continuousSign' => '1',
                'total_integral' => $this->rewards ?: 1,
                'monthDaysNum' => Carbon::now()->daysInMonth
            ],
            'record'=> ['days' => Carbon::now()->day,'everyday_integral' => $this->rewards ?: 1],
            'cte' => ['sign_integral' => ['continue7day'=>'','continue14day'=>'','continue21day'=>'']],//continue sign
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
            return $exception->getMessage();
        }
    }

    public function RepairSign($day)
    {
        if (empty($model = $this->fetchMonthSign()))//如果月没有记录 添加月记录 有 继续查询 当天有无记录
        {
            return '补签失败请选择先签到';
        }

        if ($this->verifyDay($model,$day))
        {
            return '补签无效不能选择已签到';
        }

        $ret = $this-> signRule()['retroactive'];
        if (!is_array($ret) && !in_array($this->signClass::SIGN_RETROACTIVE,$ret))
        {
            return '补签通道关闭';
        }

        $data = $this->FirstData();



    }

    public function signRule()
    {
        return $this->signClass->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->get();
    }
}