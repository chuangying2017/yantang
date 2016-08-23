<?php namespace App\Services\Traits;

trait Messages {

    protected $error_message = null;

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
    public function getErrorMessage($default = '')
    {
        return is_null($this->error_message) ? $default : $this->error_message;
    }


}
