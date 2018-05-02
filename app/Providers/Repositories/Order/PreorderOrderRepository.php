<?php namespace App\Repositories\Order;

use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\Preorder\Product\PreorderSkuCounterRepoContract;
use App\Repositories\Preorder\Product\PreorderSkusRepositoryContract;
use App\Services\Order\OrderProtocol;

class PreorderOrderRepository extends ClientOrderRepository {

    protected $detail_relations = ['skus', 'address', 'billings'];
    protected $lists_relations = ['skus'];

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE;
    }


    /**
     * @param $data
     */
    public function createOrder($data)
    {
        $order = parent::createOrder($data);
        $order->preorder = $this->attachPreorder($order, $data['preorder']);

        return $order;
    }

    /**
     * @param $order
     * @param $preorder 'user_id' => $data['user_id'],
     * 'name' => $data['name'],
     * 'phone' => $data['phone'],
     * 'address' => $data['address'],
     * 'station_id' => $data['station_id'],
     * 'start_time' => $data['start_time'],
     * 'end_time' => $data['end_time'],
     * 'weekday_type' => $data['weekday_type'],
     * 'daytime' => $data['daytime'],
     */

    private function attachPreorder($order, $preorder_data)
    {
        $preorder_data['user_id'] = $order['user_id'];
        $preorder_data['order_id'] = $order['id'];
        $preorder_data['order_no'] = $order['order_no'];
        $preorder_data['total_amount'] = $order['total_amount'];

        $preorder = app()->make(PreorderRepositoryContract::class)->createPreorder($preorder_data);

        foreach ($preorder_data['skus'] as $preorder_sku_key => $sku_counter) {
            foreach ($order->skus as $order_sku) {
                if ($order_sku['product_sku_id'] == $sku_counter['product_sku_id']) {
                    $preorder_data['skus'][$preorder_sku_key]['order_sku_id'] = $order_sku['id'];
                    $preorder_data['skus'][$preorder_sku_key]['order_id'] = $order['id'];
                }
            }
        }

        $preorder->skus = app()->make(PreorderSkusRepositoryContract::class)->createPreorderProducts($preorder['id'], $preorder_data['skus']);

        return $preorder;
    }


}
