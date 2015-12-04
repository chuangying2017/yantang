<?php namespace App\Services\Traits;

trait Messages {

    protected $error_message;

    /**
     * @param mixed $error_message
     * @return Messages
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }


}
