<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class SignIntegralRecordModel extends Model
{
    //
    protected $table = 'sign_integral_record';

    protected $guarded = ['id'];

    public function sign_month()
    {
        return $this->belongsTo(SignMonthModel::class,'sign_month_id','id');
    }
}
