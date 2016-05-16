<?php

namespace App\Models\Client\Account;

use App\Models\Client\Account\CreditsRecord;
use Illuminate\Database\Eloquent\Model;

class Credits extends Model {

    //
    protected $table = 'credits';

    protected $primaryKey = 'user_id';

    protected $guarded = ['id'];

    public function records()
    {
        return $this->hasMany(CreditsRecord::class);
    }
}
