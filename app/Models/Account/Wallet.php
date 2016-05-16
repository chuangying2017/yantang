<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $table = 'wallet';

    protected $guarded = ['id'];

    public function records()
    {
        return $this->hasMany(WalletRecord::class);
    }
}
