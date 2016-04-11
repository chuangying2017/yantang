<?php namespace App\Services\Orders\Exceptions;

use App\Services\Orders\OrderProtocol;

class WrongStatus extends \Exception {

    public function __construct($from, $to)
    {
        parent::__construct('状态变更错误, 当前状态为' . OrderProtocol::status($from) . ', 不能变更为' . OrderProtocol::status($to));
    }

}
