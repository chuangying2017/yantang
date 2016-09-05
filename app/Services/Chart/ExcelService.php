<?php namespace App\Services\Chart;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Excel;

class ExcelService {

    public static function downExcel($e_data, $title)
    {
        return Excel::create($title, function ($excel) use ($e_data) {
            $excel->sheet('data', function ($sheet) use ($e_data) {
                $sheet->fromArray($e_data);
            });
        })->export('xls');
    }

    public static function downPreorder($preorders, $title = null)
    {
        $e_datas = [];

        foreach ($preorders as $key => $preorder) {
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['姓名'] = $preorder['name'];
            $e_data['电话'] = $preorder['phone'];
            $e_data['地址'] = $preorder['address'];
            $e_data['服务部'] = array_get($preorder, 'station.name');
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['配送开始时间'] = $preorder['start_time'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price(array_get($preorder, 'order.total_amount'));
            $e_data['优惠金额'] = display_price(array_get($preorder, 'order.discount_amount') ?: 0);
            $e_data['实付价格'] = display_price(array_get($preorder, 'order.pay_amount'));
            $e_data['商品详情'] = self::getPreorderSkusArray($preorder);

            $e_datas[$key] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达订奶订单 - 导出时间:' . Carbon::now()->toDateTimeString();

        return self::downExcel($e_datas, $title);
    }

    protected static function getPreorderSkusArray($preorder)
    {
        $e_sku = '';
        foreach ($preorder['skus'] as $sku) {
            $e_sku .= $sku['name'] . ', ' . '总共: ' . $sku['total'] . ', ' . '剩余: ' . $sku['remain'] . "\n";
        }
        return $e_sku;
    }

}
