<?php

namespace App\Models\Client;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;

class UserGroupAbstract extends Model {

    protected $table = 'user_groups';

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }




}
