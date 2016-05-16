<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 9/12/2015
 * Time: 4:21 PM
 */

namespace App\Services\Client\Account;


class AccountProtocol {

    const ACCOUNT_NOT_SET = '帐号类型未设置';

    const PER_PAGE = 10;

    const ACCOUNT_TYPE_USE = 'use';
    const ACCOUNT_TYPE_CHARGE = 'charge';
    const ACCOUNT_TYPE_FROZEN = 'frozen';
    const ACCOUNT_TYPE_FROZEN_USE = 'frozen_use';
    const ACCOUNT_TYPE_UNFROZEN = 'unfrozen';
    const ACCOUNT_TYPE_WITHDRAW = 'withdraw';
    const ACCOUNT_TYPE_REFUND = 'refund';
    const ACCOUNT_NOT_ENOUGH = '余额不足';

    const ACCOUNT_AMOUNT_MAIN_NAME = 'amount';
    const ACCOUNT_AMOUNT_FROZEN_NAME = 'frozen_amount';
    const ACCOUNT_AMOUNT_USED_NAME = 'used_amount';

    const ACCOUNT_INCOME =1;
    const ACCOUNT_OUTCOME =0;
    const ACCOUNT_FROZEN_USE =2;

    const ACCOUNT_RECORD_STATUS_OF_OK = 1;
    const ACCOUNT_RECORD_STATUS_OF_CANCEL = 0;

    public static function getFlow($type)
    {
        if(in_array($type, [
            self::ACCOUNT_TYPE_USE,
            self::ACCOUNT_TYPE_FROZEN,
            self::ACCOUNT_TYPE_WITHDRAW,
        ])) {
            return self::ACCOUNT_OUTCOME;
        }

        if(in_array($type, [
            self::ACCOUNT_TYPE_CHARGE,
            self::ACCOUNT_TYPE_UNFROZEN,
            self::ACCOUNT_TYPE_REFUND,
        ])) {
            return self::ACCOUNT_OUTCOME;
        }

        if(in_array($type, [
            self::ACCOUNT_TYPE_FROZEN_USE
        ])) {
            return self::ACCOUNT_FROZEN_USE;
        }

        throw new \Exception('帐号记录类型错误');
    }

    public static function getType($from, $to)
    {
        $type = null;
        if (is_null($from)) {
            if ($to == self::ACCOUNT_AMOUNT_MAIN_NAME) {
                $type = self::ACCOUNT_TYPE_CHARGE;
            }
        } else if ($from == self::ACCOUNT_AMOUNT_MAIN_NAME) {
            if ($to == self::ACCOUNT_AMOUNT_USED_NAME) {
                $type = self::ACCOUNT_TYPE_USE;
            } elseif ($to == self::ACCOUNT_AMOUNT_FROZEN_NAME) {
                $type = self::ACCOUNT_TYPE_FROZEN;
            }
        } else if ($from == self::ACCOUNT_AMOUNT_FROZEN_NAME) {
            if ($to == self::ACCOUNT_AMOUNT_MAIN_NAME) {
                $type = self::ACCOUNT_TYPE_UNFROZEN;
            } elseif ($to == self::ACCOUNT_AMOUNT_USED_NAME) {
                $type = self::ACCOUNT_TYPE_FROZEN_USE;
            }
        } else if ($from == self::ACCOUNT_AMOUNT_USED_NAME) {
            if ($to == self::ACCOUNT_AMOUNT_MAIN_NAME) {
                $type = self::ACCOUNT_TYPE_REFUND;
            }
        }

        if (is_null($type)) {
            throw new \Exception('帐号余额流向错误');
        }

        return $type;
    }


}
