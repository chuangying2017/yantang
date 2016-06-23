<?php

namespace App\Models\Client;

use App\Services\Client\ClientProtocol;
use Illuminate\Database\Eloquent\Model;

class UserGroupAbstract extends Model {

    protected $table = 'user_groups';

    protected $guarded = ['id'];


}
