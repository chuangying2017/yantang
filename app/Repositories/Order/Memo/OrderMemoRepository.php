<?php namespace App\Repositories\Order\Memo;

use App\Models\Order\OrderMemo;

class OrderMemoRepository {

    public function setCustomerMemo($order_id, $memo)
    {
        return $this->createOrUpdateMemo($order_id, $memo, 'customer');
    }

    public function setMerchantMemo($order_id, $memo)
    {
        return $this->createOrUpdateMemo($order_id, $memo, 'merchant');
    }

    public function setSystemMemo($order_id, $memo)
    {
        return $this->createOrUpdateMemo($order_id, $memo, 'system');
    }

    protected function createOrUpdateMemo($order_id, $memo, $name)
    {
        $order_memo = OrderMemo::firstOrNew(['order_id' => $order_id]);
        $order_memo->$name = $memo;
        $order_memo->save();
        return $order_memo;
    }

}
