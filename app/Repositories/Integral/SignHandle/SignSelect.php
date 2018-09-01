<?php
namespace App\Repositories\Integral\SignHandle;

use App\Models\Access\User\UserProvider;
use App\Models\Integral\IntegralRecord;
use App\Models\Integral\SignIntegralRecordModel;
use App\Models\Integral\SignMonthModel;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;

class SignSelect extends ShareAccessRepositories
{

    protected $pagination = 20;

    protected function init()
    {
        $this->set_model(new IntegralRecord());
    }

    public function get_Sign($input)
    {
        if (!empty($input['keywords']))
        {
            $userProvider = UserProvider::query()->where('nickname','like',"%{$input['keywords']}%")->with(['integralRecord'=>function($query)use($input){
                $query->where('record_able','=',SignMonthModel::class);
                $this->selectTime($query,$input);
            }])->get();
            $arr = [];
            foreach ($userProvider as $static)
            {
                if (empty($static->integralRecord->toArray()))
                {
                    continue;
                }

                foreach ($static->integralRecord as $record)
                {
                    $record['nickname'] = $static->nickname;
                    $record['avatar'] = $static->avatar;
                }
                $arr=array_merge($arr,$static->integralRecord->toArray());
            }
            $userProvider = collect($arr);

        }else{
            $userProvider = $this->selectTime($this->model,$input);

            foreach ($userProvider->with('userProvider')->get() as &$user)
            {
                $user['nickname'] = $user->userProvider->nickname;
                $user['avatar'] = $user->userProvider->avatar;
            }
        }

        if (empty($userProvider->toArray()))
        {
            return [];
        }

        if ($userProvider instanceof IntegralRecord)
        {
            $select = 'get';
        }else{
            $select = 'all';
        }

        $data = $userProvider->forPage(array_get($input,'page',1),$this->pagination)->{$select}();
       return [
            'page' => array_get($input,'page',1),//当前分页
            'total'=> $count = $userProvider->count(),//总条数
            'total_page' => (int)ceil($count / $this->pagination),//总页数
            'data'=>$data
            ];
    }

    public function selectTime($query,$input,$field = 'created_at')
    {
        if (!empty($input['start_time']) && !empty($input['end_time']))
        {
         return   $query->whereDate($field,'>=',$input['start_time'])->whereDate($field,'<=',$input['end_time']);
        }

        if (!empty($input['start_time']) && empty($input['end_time']))
        {
         return   $query->whereDate($field,'>=',$input['start_time']);
        }

        if (empty($input['start_time']) && !empty($input['end_time']))
        {
         return   $query->whereDate($field,'<=',$input['end_time']);
        }

        return $query;
    }
}