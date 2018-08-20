<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class SignIntegralCteModel extends Model
{
    //
    protected $table='sign_continue';

    protected $fillable = ['sign_month_id','sign_integral'];

    protected $casts = ['sign_integral' => 'array'];

    public $timestamps = false;

    public function sign_month()
    {
        return $this->belongsTo(SignMonthModel::class,'sign_month_id','id');
    }
}
