<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 6:59 PM
 */

namespace App\Services\User;

use App\Services\User\WalletFactory;

class CreditWalletRepository extends WalletFactory
{
    protected $type = 'credits_wallet';
}
