<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    protected $table='company';

    protected $guarded = ['id'];

    public function scopeStatus($query,$type)
    {
        return $query->where('status','=',$type);
    }
}
