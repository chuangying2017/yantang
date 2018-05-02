<?php namespace App\Repositories\Pay;
interface RefundRepositoryContract {

    public function createRefund($charge_id, $amount, $desc = '退款');

    public function getRefund($charge_id, $refund_id = null);

    public function getRefundTransaction($refund_id);


}
