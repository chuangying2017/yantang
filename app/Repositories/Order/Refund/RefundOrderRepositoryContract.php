<?php namespace App\Repositories\Order\Refund;
interface RefundOrderRepositoryContract {

    public function updateRefundAsApprove($order_no);

    public function updateRefundAsReject($order_no);

    public function updateRefundAsRefunding($order_no);

    public function updateRefundAsDone($order_no);

    public function updateRefundAsFail($order_no);

}
