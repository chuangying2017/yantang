<?php namespace App\Repositories\Pay;
interface ChargeRepositoryContract {

    public function createCharge($amount, $order_no, $channel = null);

    public function getCharge($charge_id);

    public function chargeIsPaid($charge_id);

    public function getChargeTransaction($charge_id);

}
