<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Credits extends Model {

    //
    protected $table = 'credits';

    protected $guarded = ['id'];

    public function records()
    {
        return $this->hasMany(CreditsRecord::class);
    }
}
