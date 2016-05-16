<?php

namespace App\Models\Client\Account;

use Illuminate\Database\Eloquent\Model;

class WalletRecord extends Model {

    protected $table = 'wallet_records';

    protected $guarded = ['id'];
}
