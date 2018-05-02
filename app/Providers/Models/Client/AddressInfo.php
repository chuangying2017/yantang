<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class AddressInfo extends Model {

    protected $table = 'address_info';

    protected $primaryKey = 'address_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];
}
