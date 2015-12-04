<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model {
    use SoftDeletes;

    protected $table = 'tickets_view';

    protected $guarded = ['id'];

    public function resource()
    {
        return $this->morphTo();
    }

}
