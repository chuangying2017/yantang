<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/14/014
 * Time: 14:50
 */

namespace App\Repositories\Integral\Card;


use App\Models\Client\Account\Wallet;
use App\Models\Integral\IntegralRecord;
use App\Repositories\Member\InterfaceFile\MemberRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class CardVerify
{
    protected $model;

    protected $errorMessage = '';

    protected $verifyData = [];

    protected $record;

    protected $member;

    public function __construct(IntegralRecord $integralRecord,MemberRepositoryContract $memberRepositoryContract)
    {
        $this->record = $integralRecord;
        $this->member = $memberRepositoryContract;
    }

    public function set_model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function set_verifyData($data)
    {
        $this->verifyData = $data;
        return $this;
    }

    //判断remain
    public function estimate()
    {
        if ($this->model['remain'] <= 0)
        {
            $this->errorMessage = '库存不足';
        }
        return $this;
    }

    //判断用户领取记录是否大于限制领取数量

    //判断是否在当前的有效时间领取
    public function fetch_draw()
    {
       $count = $this->record->where('user_id','=',$this->verifyData['user_id'])->where('type_id','=',$this->verifyData['card_id'])->count();

       if ($count >= $this->model['draw_num'])
       {
            $this->errorMessage = '领取数量过多';

       }
            return $this;
    }

    //是否为新会员 新会员规定时间无下单
    public function down_date()
    {

        if (!$this->date_between(Carbon::now()->toDateTimeString(),
            [$this->model['start_time'],$this->model['end_time']]))
        {
            $this->errorMessage = '不在活动时间内';
        }
            return $this;
    }

    //有无限制新会员不可领取
    public function new_member()
    {
        if ($this->model['mode'] == CardProtocol::CARD_MODE_DEFAULT) // 木有限制
        {
            return $this;
        }elseif ($this->model['mode'] == CardProtocol::CARD_MODE_ONE)
        {
            $boolean = $this->member->new_member($this->verifyData['user_id']);
        }else
        {
            throw new \Exception('没有这个mode类型',500);
        }

        if (!$boolean)
        {
            $this->errorMessage = '新会员不可领取';
        }
            return $this;
    }

    /**
     * @return CardVerify
     * @throws \Exception
     */
    public function limitsOrLoose()
    {
        if ($this->model['type'] == CardProtocol::CARD_TYPE_LIMITS)
        {
            $this->fetch_draw();
        }
        elseif ($this->model['type'] == CardProtocol::CARD_TYPE_LOOSE)
        {

        }
        else
        {
            throw new Exception('type is not exits!类型不存在');
        }
       return $this->down_date()->estimate()->new_member();
    }

    /**
     *
     */
    public function dataCommit()
    {
        try{
            \DB::beginTransaction();
            $model = $this->model;
            $model->remain -= 1;
            $model->get_member += 1;

            $this->record->fill([
                'type_id' => $this->model->id,
                'record_able' =>get_class($this->model),
                'user_id' => $this->verifyData['user_id'],
                'name' => $this->verifyData['name'],
                'integral' => "+" . $this->model->give
            ]);

            $this->record->save();

            Wallet::query()->where('user_id','=', $this->verifyData['user_id'])->increment('integral',$this->model['give']);

            $model->save();

            \DB::commit();
        }catch (Exception $exception)
        {
            Log::error($exception->getMessage());
            \DB::rollBack();

            exit($exception->getMessage());
        }

            return true;
    }

    public function get_errorMessage()
    {
        return $this->errorMessage;
    }

    protected function date_between($verifyDate,$date = [])
    {
        $start = strtotime($date[0]);

        $end = strtotime($date[1]);

        $verify = strtotime($verifyDate);

        if ($verify >= $start && $verify <= $end)
        {
            return true;
        }
            return false;
    }
}