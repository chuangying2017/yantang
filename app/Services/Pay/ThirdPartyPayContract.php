<?php namespace App\Services\Pay;
interface ThirdPartyPayContract {

    public function isPaid($charge);

    public function getTransaction($charge);
}
