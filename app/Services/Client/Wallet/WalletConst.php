<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 9/12/2015
 * Time: 4:21 PM
 */

namespace App\Services\Client\Wallet;


class WalletConst
{
    const WALLET_NOT_SET = 'wallet not set';
    const WALLET_TYPE_FROZEN = 'frozen';
    const WALLET_TYPE_UNFROZEN = 'unfrozen';
    const WALLET_TYPE_USE = 'use';
    const WALLET_TYPE_WITHDRAW = 'withdraw';
    const WALLET_TYPE_CHARGE = 'charge';
    const WALLET_TYPE_REFUND = 'refund';
    const WALLET_NOT_ENOUGH = 'WALLET NOT ENOUGH';
}
