<?php
namespace App\Repositories\Integral\SignHandle;

use App\Models\Access\User\UserProvider;
use App\Models\Integral\SignIntegralRecordModel;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;

class SignSelect extends ShareAccessRepositories
{

    protected function init()
    {
        $this->set_model(new SignIntegralRecordModel());
    }

    public function get_Sign($input)
    {
        $signIntegralRecord = $this->model;

        if (!empty($input['start_time']) && !empty($input['end_time']))
        {
            $signIntegralRecord->whereDate('created_at','>=',$input['start_time'])->whereDate('created_at','<=',$input['end_time']);
        }

        if (!empty($input['start_time']) && empty($input['end_time']))
        {
            $signIntegralRecord->whereDate('created_at','>=',$input['start_time']);
        }

        if (!empty($input['end_time']) && empty($input['start_time']))
        {
            $signIntegralRecord->whereDate('created_at','<=',$input['end_time']);
        }

        if (!empty($input['keywords']))
        {
            UserProvider::query()->where('nickname','like',"%{$input['keywords']}%");
        }
    }
}