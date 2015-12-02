<?php namespace App\Services\Orders;

use App\Services\Orders\Exceptions\WrongStatus;

class OrderProtocol {


    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';
    const STATUS_OF_DELIVER = 'deliver';
    const STATUS_OF_RETURN_DELIVER = 'redeliver';
    const STATUS_OF_DONE = 'done';
    const STATUS_OF_REVIEW = 'review';
    const STATUS_OF_CANCEL = 'cancel';
    const STATUS_OF_REFUNDING = 'refunding';
    const STATUS_OF_REFUNDED = 'refunded';


    const TYPE_OF_DISCOUNT = 'discount';
    const TYPE_OF_MAIN = 'main';

    const RESOURCE_OF_TICKET = 'App\Models\Ticket';
    const RESOURCE_OF_PINGXX = 'App\Models\PingxxPayment';


    public static function status($key = null)
    {
        $message = [
            self::STATUS_OF_UNPAID         => '未支付',
            self::STATUS_OF_PAID           => '已支付',
            self::STATUS_OF_DELIVER        => '已发货',
            self::STATUS_OF_RETURN_DELIVER => '已退货',
            self::STATUS_OF_DONE           => '已完成',
            self::STATUS_OF_CANCEL         => '已取消',
            self::STATUS_OF_REVIEW         => '已评价',
            self::STATUS_OF_REFUNDING      => '退款中',
            self::STATUS_OF_REFUNDED       => '已退款',
        ];

        return is_null($key) ? $message : $message[ $key ];
    }

    public static function validStatus($from_status, $to_status)
    {
        $valid_status = [];
        switch ($to_status) {
            case self::STATUS_OF_PAID:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_DELIVER:
                $valid_status = [self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_DONE:
                $valid_status = [self::STATUS_OF_DELIVER];
                break;
            case self::STATUS_OF_CANCEL:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_REVIEW:
                $valid_status = [self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_REFUNDING:
                $valid_status = [self::STATUS_OF_PAID, self::STATUS_OF_DELIVER, self::STATUS_OF_RETURN_DELIVER, self::STATUS_OF_DONE, self::STATUS_OF_REVIEW];
                break;
            case self::STATUS_OF_REFUNDED:
                $valid_status = [self::STATUS_OF_REFUNDING];
                break;
            case self::STATUS_OF_UNPAID:
                $valid_status = [self::STATUS_OF_UNPAID];
                break;
            default:
                break;
        }


        if(in_array($from_status, $valid_status)){
            throw new WrongStatus($from_status, $to_status);
        }

        return true;
    }

    /**
     * Pingxx
     */


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


}
