<?php namespace App\Services\Orders\Supports;
use App\Services\Orders\Exceptions\WrongStatus;

class PingxxProtocol {

    /**
     * Pingxx
     */

    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';


    const PINGXX_APP_CHANNEL_ALIPAY = 'alipay';
    const PINGXX_APP_CHANNEL_WECHAT = 'wx';
    const PINGXX_APP_CHANNEL_UNIONPAY_NEW = 'upacp';
    const PINGXX_APP_CHANNEL_UNIONPAY_OLD = 'upmp';
    const PINGXX_APP_CHANNEL_BAIDU = 'bfb';
    const PINGXX_APP_CHANNEL_APPLE_PAY = 'apple_pay';

    const PINGXX_WAP_CHANNEL_ALIPAY = 'alipay_wap';
    const PINGXX_WAP_CHANNEL_WECHAT = 'wx_pub';
    const PINGXX_WAP_CHANNEL_UNIONPAY_NEW = 'upacp_wap';
    const PINGXX_WAP_CHANNEL_UNIONPAY_OLD = 'upmp_wap';
    const PINGXX_WAP_CHANNEL_BAIDU = 'bfb_wap';
    const PINGXX_WAP_CHANNEL_YEEPAY = 'yeepay_wap';
    const PINGXX_WAP_CHANNEL_JINGDONG = 'jdpay_wap';


    const PINGXX_PC_CHANNEL_ALIPAY = 'alipay_pc_direct';
    const PINGXX_PC_CHANNEL_UNIONPAY = 'upacp_pc';

    const PINGXX_SPECIAL_CHANNEL_ALIPAY_QR = 'alipay_qr';
    const PINGXX_SPECIAL_CHANNEL_WECHAT_QR = 'wx_pub_qr';

    const PINGXX_ACCOUNT = 'DEFAULT';

    const PAID_PACKAGE_CURRENCY = 'cny';

    public static function validStatus($from_status, $to_status)
    {
        $valid_status = [];
        switch ($to_status) {
            case self::STATUS_OF_PAID:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_UNPAID:
                $valid_status = [self::STATUS_OF_UNPAID];
                break;
            default:
                break;
        }


        if (in_array($from_status, $valid_status)) {
            throw new WrongStatus($from_status, $to_status);
        }

        return true;
    }


}
