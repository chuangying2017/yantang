<?php namespace App\Services\Notify\Config;


use App\Services\Notify\Traits\NotifyStation;

class NotifyStationNewOrder implements NotifyConfigContract {

    use NotifyStation;

    /**
     * @return string
     */
    public static function sms()
    {
        return '您好，您有一笔新的订单，请及时处理！【燕塘优鲜达】';
    }

    /**
     * @return array
     */
    public static function weixin($user_id, $preorder = null)
    {
        if (is_null($preorder)) {
            return false;
        }

        $open_id = self::getProviderId($user_id);
        if (!$open_id) {
            return false;
        }

        return [
            'touser' => $open_id,
            'template_id' => 'RMTBkGBLeda33lYCBMx2sH-poS7WKVsEunaivyIAfRo',
            'url' => api_route('api.stations.preorders.show', 'v1', [$preorder['id']]),
            'topcolor' => '#f7f7f7',
            'data' => [
                "first" => "您好，您有一笔新的订单，请及时处理",
                "keyword1" => $preorder['name'],
                "keyword2" => $preorder['phone'],
                "keyword3" => $preorder['address'],
                "keyword4" => $preorder['start_time'],
                'remark' => '请在12小时内处理订单!'
            ],
        ];
    }


}
