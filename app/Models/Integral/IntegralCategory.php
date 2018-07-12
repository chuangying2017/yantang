<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class IntegralCategory extends Model
{
    //
    protected $table = 'integral_category';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::addGlobalScope('status',function(Builder $builder){
            $builder->where('status','=','show');
        });
    }
}
