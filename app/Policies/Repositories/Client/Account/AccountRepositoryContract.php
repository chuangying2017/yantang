<?php namespace App\Repositories\Client\Account;

use App\Services\Client\Account\AccountProtocol;

interface AccountRepositoryContract {

    public function createAccount();

    public function getAccount();

    public function getAmount($amount_name = null);

    public function change($amount, $resource_type, $resource_id, $to, $from);

    public function getAllRecords($type = null, $order_by = 'created_at', $sort = 'desc');

    public function getRecord($billing_id, $type);

    public function getRecordsPaginated($type, $order_by = 'created_at', $sort = 'desc', $per_page = AccountProtocol::PER_PAGE);

	/**
     * @param $user
     * @return $this
     */
    public function setUserId($user);
}
