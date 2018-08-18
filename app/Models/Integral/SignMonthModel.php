<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class SignMonthModel extends Model
{
    //
    protected $table = 'sign_month';

    protected $guarded = ['id'];

    public function sign_cte() //连续签到奖励
    {
        return $this->hasOne(SignIntegralCteModel::class,'sign_month_id','id');
    }

    public function sign_integral_record()
    {
        return $this->hasMany(SignIntegralRecordModel::class,'sign_month_id','id');
    }
}
