<?php namespace App\Repositories\Client\Account\Traits;

trait AccountModel {

    protected $account_mode = null;
    protected $account_records_model = null;

    /**
     * @param null $account_mode
     * @return AccountModel
     */
    public function setAccountMode($account_mode)
    {
        $this->account_mode = $account_mode;
        return $this;
    }

    /**
     * @return null
     */
    public function getAccountMode()
    {
        return $this->account_mode;
    }

    /**
     * @param null $account_records_model
     * @return AccountModel
     */
    public function setAccountRecordsModel($account_records_model)
    {
        $this->account_records_model = $account_records_model;
        return $this;
    }

    /**
     * @return null
     */
    public function getAccountRecordsModel()
    {
        return $this->account_records_model;
    }


}
