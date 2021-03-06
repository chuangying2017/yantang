<?php namespace App\Services\Pay\Pingxx;

use App\Exceptions\WrongStatus;

class PingxxProtocol {

    /**
     * Pingxx
     */

    const LIVE_MODE = 1;
    const TEST_MODE = 0;

    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';

    const STATUS_OF_REFUND_SUCCESS = 'succeeded';
    const STATUS_OF_REFUND_PENDING = 'pending';

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

    public static function validChannel($channel, $agent = null)
    {
        if (!$channel || is_null($channel) || is_null(self::agent($agent, $channel))) {
            throw new \Exception('支付渠道不存在');
        }

        return $channel;
    }

    const AGENT_OF_PC = 'pc';
    const AGENT_OF_MOBILE = 'mobile';
    const AGENT_OF_APP = 'app';

    public static function getChannelNo($channel)
    {
        self::validChannel($channel);

        $channels = [
            self::PINGXX_SPECIAL_CHANNEL_ALIPAY_QR => '01',
            self::PINGXX_SPECIAL_CHANNEL_WECHAT_QR => '02',
            self::PINGXX_PC_CHANNEL_ALIPAY => '03',
            self::PINGXX_PC_CHANNEL_UNIONPAY => '04',
            self::PINGXX_WAP_CHANNEL_ALIPAY => '05',
            self::PINGXX_WAP_CHANNEL_WECHAT => '06',
            self::PINGXX_WAP_CHANNEL_UNIONPAY_NEW => '07',
            self::PINGXX_WAP_CHANNEL_BAIDU => '08',
            self::PINGXX_WAP_CHANNEL_YEEPAY => '09',
            self::PINGXX_WAP_CHANNEL_JINGDONG => '10',
            self::PINGXX_APP_CHANNEL_ALIPAY => '11',
            self::PINGXX_APP_CHANNEL_WECHAT => '12',
            self::PINGXX_APP_CHANNEL_UNIONPAY_NEW => '13',
            self::PINGXX_APP_CHANNEL_BAIDU => '14',
            self::PINGXX_APP_CHANNEL_APPLE_PAY => '15',
        ];

        return $channels[$channel];
    }


    public static function agent($agent = self::AGENT_OF_MOBILE, $channel = null)
    {
        switch ($agent) {
            case self::AGENT_OF_PC:
                $data = [
                    self::PINGXX_SPECIAL_CHANNEL_ALIPAY_QR => '支付宝扫码支付',
                    self::PINGXX_SPECIAL_CHANNEL_WECHAT_QR => '微信扫码支付',
                    self::PINGXX_PC_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_PC_CHANNEL_UNIONPAY => '银联'
                ];
                break;
            case self::AGENT_OF_MOBILE:
                $data = [
                    self::PINGXX_WAP_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_WAP_CHANNEL_WECHAT => '微信支付',
                    self::PINGXX_WAP_CHANNEL_UNIONPAY_NEW => '银联支付',
                    self::PINGXX_WAP_CHANNEL_BAIDU => '百度支付',
                    self::PINGXX_WAP_CHANNEL_YEEPAY => '易付宝',
                    self::PINGXX_WAP_CHANNEL_JINGDONG => '京东支付',
                ];
                break;
            case self::AGENT_OF_APP:
                $data = [
                    self::PINGXX_APP_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_APP_CHANNEL_WECHAT => '微信支付',
                    self::PINGXX_APP_CHANNEL_UNIONPAY_NEW => '银联支付',
                    self::PINGXX_APP_CHANNEL_BAIDU => '百度支付',
                    self::PINGXX_APP_CHANNEL_APPLE_PAY => 'Apple Pay',
                ];
                break;
            default:
                $data = [
                    self::PINGXX_SPECIAL_CHANNEL_ALIPAY_QR => '支付宝扫码支付',
                    self::PINGXX_SPECIAL_CHANNEL_WECHAT_QR => '微信扫码支付',
                    self::PINGXX_PC_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_PC_CHANNEL_UNIONPAY => '银联',
                    self::PINGXX_WAP_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_WAP_CHANNEL_WECHAT => '微信支付',
                    self::PINGXX_WAP_CHANNEL_UNIONPAY_NEW => '银联支付',
                    self::PINGXX_WAP_CHANNEL_BAIDU => '百度支付',
                    self::PINGXX_WAP_CHANNEL_YEEPAY => '易付宝',
                    self::PINGXX_WAP_CHANNEL_JINGDONG => '京东支付',
                    self::PINGXX_APP_CHANNEL_ALIPAY => '支付宝',
                    self::PINGXX_APP_CHANNEL_WECHAT => '微信支付',
                    self::PINGXX_APP_CHANNEL_UNIONPAY_NEW => '银联支付',
                    self::PINGXX_APP_CHANNEL_BAIDU => '百度支付',
                    self::PINGXX_APP_CHANNEL_APPLE_PAY => 'Apple Pay',
                ];
        }

        return is_null($channel) ? $data : (isset($data[$channel]) ? $data[$channel] : null);
    }

    public static function isSucceed($charge)
    {
        return is_null($charge->failure_code);
    }

    public static function isDone($charge)
    {
        return $charge->succeed;
    }

}

