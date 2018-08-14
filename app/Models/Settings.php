<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    //
    protected $table = 'settings';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','key','value'];

    /**
     * transform array or json
     *
     * */
    protected $casts = ['value'=>'json'];
}
