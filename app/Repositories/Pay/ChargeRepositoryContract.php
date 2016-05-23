<?php namespace App\Repositories\Pay;
interface ChargeRepositoryContract {

    public function createCharge($amount, $order_no, $channel = null);

    public function getCharge($charge_id);

    public function isPaid($charge_id);

    public function getTransaction($charge_id);

}
