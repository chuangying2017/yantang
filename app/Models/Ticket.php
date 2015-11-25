<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    protected $table = 'tickets_view';

    protected $guarded = ['id'];

    public function resource()
    {
        return $this->morphTo();
    }

}
