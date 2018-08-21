<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21/021
 * Time: 16:50
 */

namespace App\Repositories\IntegralMode;


use App\Models\Client\Account\Wallet;
use App\Models\Integral\IntegralRecord;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class IntegralMode
{

    protected $save = ['increase','decrease'];

    protected $array = ['increase' => '+', 'decrease' => '-'];

    protected $integral = ['increase' => '后台赠送积分', 'decrease' => '后台扣除积分'];

    public function verifyData($data)
    {
      $data = $this->array_($data);
        try{
            $result = 'failure';

            \DB::beginTransaction();
            foreach (array_keys($data) as $key => $array_key)
            {
                if (array_key_exists($array_key,$this->save))
                {
                    $this->memberIntegral($data['user_id'])->{$array_key}('integral',$data[$array_key]);
                    $result = $this->integralRecord([
                        'type_id' => '0',
                        'record_able' => '0',
                        'user_id' => $data['user_id'],
                        'name'  => $this->integral[$array_key],
                        'integral' => $this->array[$array_key] . $data[$array_key],
                        'type' => 'admin',
                        'role_name' => $data['username']
                    ]);
                    break;
                }
            }
            \DB::commit();
            return $result;
        }catch (Exception $exception)
        {
            \DB::rollBack();
            Log::error($exception->getMessage());
        }


    }

    public function array_($data)
    {
        return array_only($data,[
            'increase','decrease','user_id','username','name'
        ]);
    }

    public function memberIntegral($user_id)
    {
      return  Wallet::query()->where('user_id','=',$user_id);
    }

    public function integralRecord($data)
    {
       return IntegralRecord::create($data);
    }
}