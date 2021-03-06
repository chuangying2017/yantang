<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nav extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'navs';

    public function scopeParent($query) 
    {
    	return $query->where('pid', 0);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Nav', 'pid', 'id');
    }
}
