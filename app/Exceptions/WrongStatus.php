<?php namespace App\Exceptions;

class WrongStatus extends \Exception {

    public function __construct($from, $to)
    {
        parent::__construct('状态变更错误, 当前状态为' . $from . ', 不能变更为' . $to);
    }

}
