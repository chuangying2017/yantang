<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 6:59 PM
 */

namespace App\Services\Wallet;

use App\Services\Wallet;

class CreditWalletRepository extends WalletFactory
{
    protected $type = 'credits_wallet';
}
