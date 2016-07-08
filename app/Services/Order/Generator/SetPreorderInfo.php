<?php namespace App\Services\Order\Generator;

use Carbon\Carbon;

class SetPreorderInfo extends GenerateHandlerAbstract {

    public function handle(TempOrder $temp_order)
    {
        $address = $temp_order->getAddress();
        $address->load('info');

        $preorder = $temp_order->getPreorder();
        $preorder['station_id'] = $address['info']['station_id'];
        $preorder['name'] = $address['name'];
        $preorder['phone'] = $address['phone'];
        $preorder['address'] = $address['district'] . $address['detail'];
        $preorder['district_id'] = $address['info']['district_id'];

        $temp_order = $this->setPreorderSkusAndCalEndTime($temp_order, $preorder);

        return $this->next($temp_order);
    }

    protected function setPreorderSkusAndCalEndTime(TempOrder $temp_order, $preorder)
    {
        $skus = $temp_order->getSkus();
        $max_day = 100000;
        $preorder_skus = [];
        foreach ($skus as $key => $sku) {
            $current_day = ceil($sku['quantity'] / $sku['per_day']);
            if ($max_day > ($current_day)) {
                $max_day = $current_day;
            }

            $skus[$key]['price'] = $sku['subscribe_price'];

            $preorder_skus[$sku['id']] = [
                'name' => $sku['name'],
                'cover_image' => $sku['cover_image'],
                'price' => $sku['price'],
                'quantity' => $sku['per_day'],
                'product_sku_id' => $sku['id'],
                'product_id' => $sku['product_id'],
                'total' => $sku['quantity'],
                'remain' => $sku['quantity']
            ];

        }
        $preorder['end_time'] = Carbon::createFromFormat('Y-m-d', $temp_order->getPreorder('start_time'))->addDays($max_day)->toDateString();
        $preorder['skus'] = $preorder_skus;

        $temp_order->setPreorder($preorder);
        $temp_order->setSkus($skus);

        return $temp_order;
    }

}
