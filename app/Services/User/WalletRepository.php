<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 6:58 PM
 */

namespace App\Services\User;

use App\Services\User\WalletFactory;

class WalletRepository extends WalletFactory
{
    protected $type = 'wallet';
}
