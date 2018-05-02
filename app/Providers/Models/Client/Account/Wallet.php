<?php

namespace App\Models\Client\Account;

use App\Models\Client\Account\WalletRecord;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $table = 'wallet';

    protected $primaryKey = 'user_id';

    protected $guarded = ['id'];

    public function records()
    {
        return $this->hasMany(WalletRecord::class);
    }
}
