<?php namespace App\Services\Pay\Exception;
class NotEnoughException extends \Exception{

    public function __construct()
    {
        parent::__construct('余额不足');
    }

}
