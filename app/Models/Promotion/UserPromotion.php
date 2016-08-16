<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class UserPromotion extends Model
{
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $table = 'user_promotion';
    
    protected $guarded = [];

}
