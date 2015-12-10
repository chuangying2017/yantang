<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 6:59 PM
 */

namespace App\Services\Client\Wallet;

use App\Models\Client;
use DB;
use Exception;

abstract class WalletFactory
{

    /**
     * wallet type; cash or credit
     * @var null
     */
    protected $type = null;

    /**
     * user id
     * @var
     */
    protected $user_id;

    /**
     * WalletFactory constructor.
     * @param $id
     * @throws \Exception
     * @internal param $wallet
     */
    public function __construct($id)
    {
        if (!$this->type) throw new Exception(WalletConst::WALLET_NOT_SET);
        $this->user_id = $id;
        $this->modelTable = $this->type;
        $this->recordTable = $this->type . '_record';
    }

    /**
     * spend money with wallet
     * @param $amount
     * @param $type
     * @return bool
     */
    public function spend($amount, $type)
    {
        try {
            DB::beginTransaction();

            if ($this->enough('amount', $amount)) {
                $this->decrement('amount', $amount);
                $this->addRecord($amount, 0, $type);
            } else {
                throw new Exception(WalletConst::WALLET_NOT_ENOUGH);
            }
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * frozen money in wallet
     * @param $amount
     * @return bool
     */
    public function frozen($amount)
    {
        try {
            DB::beginTransaction();

            if ($this->enough('amount', $amount)) {
                $this->decrement('amount', $amount);
                $this->increment('frozen_amount', $amount);
                $this->addRecord($amount, 0, WalletConst::WALLET_TYPE_FROZEN);
            } else {
                throw new Exception(WalletConst::WALLET_NOT_ENOUGH);
            }

            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * unfrozen moeny in wallet
     * @param $amount
     * @return bool
     */
    public function unFrozen($amount)
    {
        try {
            DB::beginTransaction();

            if ($this->enough('frozen_amount', $amount)) {
                $this->increment('amount', $amount);
                $this->decrement('frozen_amount', $amount);
                $this->addRecord($amount, 0, WalletConst::WALLET_TYPE_UNFROZEN);
            } else {
                throw new Exception(WalletConst::WALLET_NOT_ENOUGH);
            }

            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * add money in wallet
     * @param $amount
     * @param $type
     * @return bool
     */
    public function add($amount, $type)
    {
        try {
            DB::beginTransaction();

            $this->increment('amount', $amount);
            $this->addRecord($amount, 1, $type);

            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return 0;
        }
    }

    /**
     * add wallet hanel record
     * @param $amount
     * @param int $income
     * @param $type
     * @param string $status
     * @throws Exception
     */
    protected function addRecord($amount, $income = 0, $type, $status = '')
    {
        try {
            DB::table($this->recordTable)->insert([
                'user_id' => $this->user_id,
                'amount' => $amount,
                'income' => $income,
                'type' => $type,
                'status' => $status
            ]);
        } catch (Exception $e) {
            throw new Exception('add wallet record error: ' . $e->getMessage());
        }
    }

    /**
     * @param $column
     * @param $amount
     */
    private function increment($column, $amount)
    {
        DB::table($this->modelTable)->where('user_id', $this->user_id)->increment($column, $amount);
    }

    /**
     * @param $column
     * @param $amount
     */
    private function decrement($column, $amount)
    {
        DB::table($this->modelTable)->where('user_id', $this->user_id)->decrement($column, $amount);
    }

    public function show()
    {
        return DB::table($this->modelTable)->where('user_id', $this->user_id)->lists('amount', 'frozen_amount');
    }

    private function enough($account, $amount)
    {
        return DB::table($this->modelTable)->where('user_id', $this->user_id)->lists($account)[0] >= $amount;
    }
}
