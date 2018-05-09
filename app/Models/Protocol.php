<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    //
    protected $table = 'protocol';

    protected $primaryKey = '';

    public $timestamps = false;

    protected $dateFormat = 'datetime';
}
