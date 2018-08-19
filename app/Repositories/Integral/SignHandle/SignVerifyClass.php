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
    }

    protected function init()
    {
       $this->set_model(new SignMonthModel);

       $this->set_UserInfo();

       $this->rewards = $this->signIfFirst();
    }

    protected function set_UserInfo()
    {
        $this->array['user_id'] = access()->id();
    }

    public function verifyUserToday()
    {
        if (empty($model = $this->fetchMonthSign()))
        {
          $result = $this->SaveData($this->FirstData(),$this->fetchMethods('create'));
        }

    }


    public function verifyToDayExists($SignModel)
    {
        $result = $SignModel->withCount(['sign_integral_record' => function($query){
            $query->where('days','=',Carbon::now()->day);
        }]);

        if ($result)
        {
            $this->errorMessage = '今天已签到过！';
        }
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
                'continueSign' => '1',
                'total_integral' => $this->rewards ?: 1,
                'monthDaysNum' => Carbon::now()->daysInMonth
            ],
            'record'=> ['days' => Carbon::now()->day,'everyday_integral' => $this->rewards ?: 1],
            'cte' => []
        ];
    }

    protected function UpdateData()
    {
        return [
            
        ];
    }

    protected function signIfFirst()
    {
        $res = $this->model->where('user_id','=',$this->array['user_id'])->first();

        $get = $this->signClass->setFile(config('services.localStorageFile.SignRule'))->setPath(config('services.localStorageFile.path'))->get();
        $status = $get['extend_rule']['firstRewards'];
        if ($res)
        {
            return $status['everyday']; //正常奖励
        }else
        {
            return $status['status'] == 1 ? $status['rewards'] : $status['everyday']; //首次奖励
        }
    }

    public function SaveData($data,$method)
    {
        try{
            \DB::beginTransaction();

            $this->signSave->data = $data;

            array_reduce($method,function($v1,$v2)
            {
                $this->signSave->{$v2}($this->model);
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

    protected function fetchMethods($mode)
    {
        $create = get_class_methods($this->signSave);
        switch ($mode)
        {
            case 'create':
                return $create;

            case 'update':
                array_shift($create);
                return $create;

            default:
                throw new Exception('没有可选类型',500);

        }
    }
}