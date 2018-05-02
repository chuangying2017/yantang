<?php namespace App\Services\Chart;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Excel;

class ExcelService {

    use InvoiceExcelTrait;

    public static function getFile($file, $local = false)
    {
        if ($local) {
            return self::getLocalFullPath($file);
        }

        return self::getCDNFullPath($file);
    }

    public static function save($sheetsData, $filename, $local_path){
        return Excel::create($filename, function ($excel) use ($sheetsData) {
            foreach( $sheetsData as $sheetName => $sheetData ){
                $excel->sheet($sheetName, function ($sheet) use ($sheetData) {
                    $sheet->fromArray($sheetData);
                });
            };
        })->store('xls', $local_path);
    }

    public static function download( $filename, $local = false){
        if ($local) {
            return self::downloadLocalFile(self::getFile($filename, true));
        }

        $result = \Storage::drive('qiniu')->put($filename, file_get_contents(self::getFile($filename, true)));

        return self::getFile($filename, $local);
    }

    public static function saveAndDownload($e_data = null, $title, $path, $local = false)
    {
        $full_file_name = $path . $title . '.xls';
        $local_path = storage_path('app/' . $path);

        if ($e_data) {
            self::save(['data'=>$e_data], $title, $local_path);
        }

        return self::download( $full_file_name, $local );
    }

    public static function downExcel($e_data, $title)
    {
        return Excel::create($title, function ($excel) use ($e_data) {
            $excel->sheet('data', function ($sheet) use ($e_data) {
                $sheet->fromArray($e_data);
            });
        })->export('xls');
    }


    protected static function getPreorderSkusArray($skus)
    {
        $e_sku = '';
        foreach ($skus as $sku) {
            $per_day = '';
            if(isset($sku['per_day'])){
                $per_day = ', 每日配送: ' . $sku['per_day'];
            }
            $e_sku .= $sku['name'] . ', ' . '总共: ' . $sku['total'] . ', ' . '剩余: ' . $sku['remain'] . $per_day . "\n";
        }
        return $e_sku;
    }

    protected static function getCollectOrderSku($sku)
    {
        $e_sku = $sku['name'] . ', ' . '总共: ' . $sku['quantity'] . ' ' . $sku['unit'];
        return $e_sku;
    }

    public static function downPreorder($preorders, $title = null, $expand_skus = false)
    {
        $e_datas = [];

        $preorders->load([
            'order' => function ($query) {
                $query->select('id', 'order_no', 'total_amount', 'discount_amount', 'status', 'pay_amount');
            },
            'skus' => function ($query) {
                $query->select('order_id', 'name', 'total', 'remain', 'per_day');
            },
            'station' => function ($query) {
                $query->select('id', 'name');
            },
            'residence' => function($query){
                $query->select(['id', 'name','district_id']);
            },
            'residence.district' => function($query){
                $query->select(['id','name']);
            },
            'user.providers' => function($query){
                $query->select('user_id','provider_id');
            }]);

        $preorders = $preorders->toArray();
        foreach ($preorders as $key => $preorder) {
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['open_id'] = array_get($preorder,'user.providers.0.provider_id');
            $e_data['姓名'] = $preorder['name'];
            $e_data['电话'] = $preorder['phone'];
            $e_data['地址'] = $preorder['address'];
            $e_data['小区名称'] = is_null($preorder['residence']) ? null : $preorder['residence']['district']['name'].$preorder['residence']['name'];
            $e_data['服务部'] = array_get($preorder, 'station.name');
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['接单时间'] = $preorder['confirm_at'];
            $e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price($preorder['total_amount']);
            $e_data['优惠金额'] = display_price(array_get($preorder, 'order.discount_amount') ?: 0);
            $e_data['实付价格'] = display_price(array_get($preorder, 'order.pay_amount'));
            $e_data['商品详情'] = self::getPreorderSkusArray($preorder['skus']);

            $e_data['订购商品总数量'] = array_sum(array_pluck($preorder['skus'], 'total'));
            $e_data['每日配送总数量'] = array_sum(array_pluck($preorder['skus'], 'per_day'));

            if ($expand_skus) {
                foreach ($preorder['skus'] as $sku) {
                    $e_data['商品名称'] = $sku['name'];
                    $e_data['商品数量'] = $sku['total'];
                }
            }
            $e_datas[] = $e_data;
        }
        if (!$preorders) {
            $e_data = [];
            $e_data['订单号'] = '无结果';
            $e_data['open_id'] = '';
            $e_data['姓名'] = '';
            $e_data['电话'] = '';
            $e_data['地址'] = '';
            $e_data['小区名称'] = '';
            $e_data['服务部'] = '';
            $e_data['下单时间'] = '';
            $e_data['接单时间'] = '';
            $e_data['配送状态'] = '';
            $e_data['订单总价'] = '';
            $e_data['优惠金额'] = '';
            $e_data['实付价格'] = '';
            $e_data['商品详情'] = '';
            $e_data['订购商品总数量'] = '';
            $e_data['每日配送总数量'] = '';
            $e_datas[] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达订奶订单 - 导出时间:' . Carbon::now()->toDateTimeString();
        return self::saveAndDownload($e_datas, $title, 'preorders/', true);
    }

    public static function downCollectOrder($collectOrders, $title = null, $expand_skus = false)
    {
        $e_datas = [];
        $collectOrders->load([
            'order.user.providers' => function($query){
                $query->select(['user_id','provider_id']);
            },
            'residence.district' => function($query){
                $query->select(['id','name']);
            }
        ]);

        $collectOrders = $collectOrders->toArray();
        foreach ($collectOrders as $key => $collectOrder) {
            $open_id = array_get($collectOrder,'order.user.providers.0.provider_id');
            $address = [
                $collectOrder['address']['district'],
                $collectOrder['address']['detail'],
            ];
            $e_data['订单号'] = $collectOrder['order']['order_no'];
            $e_data['open_id'] = $open_id;
            $e_data['姓名'] = $collectOrder['address']['name'];
            $e_data['电话'] = $collectOrder['address']['phone'];
            $e_data['地址'] = join('', $address);
            $e_data['小区名称'] = is_null($collectOrder['residence']) ? null : $collectOrder['residence']['district']['name'].$collectOrder['residence']['name'];
            $e_data['服务部'] = $collectOrder['staff']['station']['name'];
            $e_data['接单时间'] = $collectOrder['pay_at'];
            $e_data['订单总价'] = display_price(array_get($collectOrder, 'order.total_amount'));
            $e_data['优惠金额'] = display_price(array_get($collectOrder, 'order.discount_amount') ?: 0);
            $e_data['实付价格'] = display_price(array_get($collectOrder, 'order.pay_amount'));
            $e_data['商品详情'] = $collectOrder['sku']['name'] . ' ' . $collectOrder['quantity'] . $collectOrder['sku']['unit'];

            if ($expand_skus) {
                foreach ($preorder['sku'] as $sku) {
                    $e_data['商品名称'] = $sku['name'];
                    $e_data['商品数量'] = $sku['total'];
                }
            }
            $e_datas[] = $e_data;
        }
        if (!$collectOrders) {
            $e_data = [];
            $e_data['订单号'] = '无结果';
            $e_data['open_id'] = '';
            $e_data['姓名'] = '';
            $e_data['电话'] = '';
            $e_data['地址'] = '';
            $e_data['小区名称'] = '';
            $e_data['服务部'] = '';
            $e_data['接单时间'] = '';
            $e_data['订单总价'] = '';
            $e_data['优惠金额'] = '';
            $e_data['实付价格'] = '';
            $e_data['商品详情'] = '';
            $e_datas[] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达收款订单 - 导出时间:' . Carbon::now()->toDateTimeString();
        return self::saveAndDownload($e_datas, $title, 'collectOrders/', true);
    }

    public static function downloadPreorderBounce($preorders, $title = null)
    {
        $e_datas = [];

        $preorders->load([
            'skus' => function ($query) {
                $query->select('product_sku_id', 'order_id', 'name', 'total');
            },
            'skus.sku' => function ($query) {
                $query->withTrashed()->select('id', 'price', 'settle_price');
            },
            'order' => function ($query) {
                $query->select('id', 'discount_amount', 'pay_amount', 'total_amount');
            }
        ]);

        foreach ($preorders as $key => $preorder) {
            $e_data['订单号'] = $preorder['order_no'];
            $e_data['下单时间'] = $preorder['created_at'];
            $e_data['接单时间'] = $preorder['confirm_at'];
            $e_data['订单总价'] = display_price($preorder['total_amount']);
            $e_data['优惠总价'] = display_price($preorder['order']['discount_amount']);
            $e_data['实付总价'] = display_price($preorder['order']['pay_amount']);

            foreach ($preorder['skus'] as $sku) {
                $e_data['产品名'] = $sku['name'];
                $e_data['数量'] = $sku['total'];
                $e_data['原价'] = display_price($sku['sku']['price']);
                $e_data['出厂价'] = display_price($sku['sku']['settle_price']);
                $e_data['结算金额'] = display_price($sku['sku']['settle_price'] * $sku['total']);
                $e_data['提成金额'] = $e_data['结算金额'] * 0.04;
            }

            $e_datas[] = $e_data;
        }

        $title = $title ?: '燕塘优鲜达威臣销售提成-' . Carbon::now()->toDateTimeString();
        return self::downExcel($e_datas, $title);
    }


    protected static function getLocalFullPath($file)
    {
        if (file_exists(storage_path('app/' . $file))) {
            return storage_path('app/' . $file);
        }
        return false;
    }

    protected static function getCDNFullPath($file)
    {
        $qiniu = \Storage::drive('qiniu');
        if ($qiniu->exists($file)) {
            return $qiniu->downloadUrl($file);
        }

        return false;
    }

    public static function downloadLocalFile($file)
    {
        return response()->download($file);
    }
    
    
    
    public static function downCardOrder($orders, $title = null, $expand_skus = false)
    {
        $e_datas = [];
    
        $orders = array_map('get_object_vars', $orders);
        
        foreach ($orders as $key => $order) {
            $e_data['订单号'] = $order['order_no'];
            $e_data['姓名'] = $order['name'];
            $e_data['电话'] = $order['phone'];
            $e_data['地址'] = $order['district'].$order['detail'];
            
            $e_data['下单时间'] = $order['created_at'];
   
            //$e_data['配送状态'] = PreorderProtocol::status($preorder['status']);
            $e_data['订单总价'] = display_price($order['total_amount']);
            $e_data['优惠金额'] = display_price($order['discount_amount']);
            $e_data['实付价格'] = display_price($order['pay_amount']);
  
            $e_datas[] = $e_data;
        }
        if (!$orders) {
            $e_data = [];
            $e_data['订单号'] = '无结果';
            $e_data['姓名'] = '';
            $e_data['电话'] = '';
            $e_data['地址'] = '';
          
            $e_data['下单时间'] = '';
      
            
            $e_data['订单总价'] = '';
            $e_data['优惠金额'] = '';
            $e_data['实付价格'] = '';

            $e_datas[] = $e_data;
        }
    
        $title = $title ?: '燕塘优鲜达订奶订单 - 导出时间:' . Carbon::now()->toDateTimeString();
        return self::saveAndDownload($e_datas, $title, 'preorders/', true);
    }


}
