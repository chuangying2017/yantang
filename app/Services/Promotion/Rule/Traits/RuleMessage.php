<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/31/16
 * Time: 8:24 PM
 */

namespace App\Services\Promotion\Traits;


trait RuleMessage {

    protected $message = '';

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
