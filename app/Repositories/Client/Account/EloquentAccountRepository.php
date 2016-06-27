<?php namespace App\Repositories\Client\Account;

use App\Services\Client\Account\AccountProtocol;
use Exception;

abstract class EloquentAccountRepository implements AccountRepositoryContract {

    /**
     * type; cash or credit
     * @var null
     */

    protected $account_model = null;
    protected $account_record_model = null;
    protected $user_id = null;

    /**
     * 初始化
     */
    protected abstract function init();

    /**
     * WalletFactory constructor.
     * @param $id
     * @throws \Exception
     * @internal param $wallet
     */
    public function __construct()
    {
        $this->init();
        if (!$this->getAccountModel() || !$this->getAccountRecordModel()) {
            throw new Exception(AccountProtocol::ACCOUNT_NOT_SET);
        }
    }

    /**
     * @return $mix;
     */
    public function createAccount()
    {
        $model = $this->getAccountModel();
        return $model::updateOrCreate(
            ['user_id' => $this->getUserId()],
            [
                'amount' => 0
            ]
        );
    }

    /**
     * @return $mix;
     */
    public function getAccount()
    {
        $model = $this->getAccountModel();

        return $model::firstOrCreate(
            ['user_id' => $this->getUserId()]
        );
    }

    public function getAmount($amount_name = AccountProtocol::ACCOUNT_AMOUNT_MAIN_NAME)
    {
        $account = $this->getAccount();
        return array_get($account->toArray(), $amount_name, 0);
    }

    /**
     * @param $amount
     * @param $resource_type
     * @param $resource_id
     * @param string $to
     * @param null $from
     * @return mixed
     * @throws Exception
     */
    public function change($amount, $resource_type, $resource_id, $to = AccountProtocol::ACCOUNT_AMOUNT_MAIN_NAME, $from = null)
    {
        $account = $this->getAccount();
        $account->$to = $account->$to + $amount;
        if (!is_null($from)) {
            $account->$from = $account->$from - $amount;
        }

        $record = $this->createRecord($amount, $resource_type, $resource_id, AccountProtocol::getType($from, $to));
        $account->save();

        return $record;
    }

    public function getAllRecords($type = null, $order_by = 'created_at', $sort = 'desc')
    {
        return $this->queryRecords($type, $order_by, $sort);
    }

    public function getRecordsPaginated($type, $order_by = 'created_at', $sort = 'desc', $per_page = 20)
    {
        return $this->queryRecords($type, $order_by, $sort, $per_page);
    }

    protected function queryRecords($type, $order_by = 'created_at', $sort = 'desc', $per_page = AccountProtocol::PER_PAGE)
    {
        $model = $this->getAccountRecordModel();
        $query = $model::query();
        if (!is_null($type)) {
            $query = $query->where('type', $type);
        }
        $query = $query->orderBy($order_by, $sort);

        if (!empty($this->user_id)) {
            $query = $query->where('user_id', $this->user_id);
        }

        if (is_null($per_page)) {
            return $query->get();
        }
        return $query->paginate($per_page);
    }

    public function getRecord($billing_id, $billing_type)
    {
        $record_model = $this->getAccountRecordModel();
        return $record_model->where('resource_type', $billing_type)->where('resource_id', $billing_id)->first();
    }

    protected function createRecord($amount, $resource_type, $resource_id, $type)
    {
        $record_model = $this->getAccountRecordModel();

        if ($record_model::where(['resource_type' => $resource_type, 'resource_id' => $resource_id])->first()) {
            throw new \Exception('重复处理账单');
        }

        $record = $record_model::create([
            'user_id' => $this->getUserId(),
            'amount' => $amount,
            'income' => AccountProtocol::getFlow($type),
            'resource_type' => $resource_type,
            'resource_id' => $resource_id,
            'type' => $type,
            'status' => AccountProtocol::ACCOUNT_RECORD_STATUS_OF_OK
        ]);

        return $record;
    }

    /**
     * @param mixed $user_id
     * @return EloquentAccountRepository
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param null $account_model
     * @return EloquentAccountRepository
     */
    public function setAccountModel($account_model)
    {
        $this->account_model = $account_model;
        return $this;
    }

    /**
     * @return null
     */
    public function getAccountModel()
    {
        return $this->account_model;
    }

    /**
     * @return null
     */
    public function getAccountRecordModel()
    {
        return $this->account_record_model;
    }

    /**
     * @param null $account_record_model
     */
    public function setAccountRecordModel($account_record_model)
    {
        $this->account_record_model = $account_record_model;
        return $this;
    }


}
