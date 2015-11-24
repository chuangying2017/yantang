<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 20/11/2015
 * Time: 6:59 PM
 */

namespace App\Services\Wallet;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;

abstract class WalletFactory
{
    const WALLET_TYPE_FROZEN = 'frozen';
    const WALLET_TYPE_UNFROZEN = 'unfrozen';
    const WALLET_TYPE_USE = 'use';
    const WALLET_TYPE_WITHDRAW = 'withdraw';
    const WALLET_TYPE_CHARGE = 'charge';
    const WALLET_TYPE_REFUND = 'refund';

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
        if (!$this->type) throw new \Exception('no wallet type set');
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

            DB::table($this->modelTable)->decrement('amount', $amount);
            $this->addRecord($amount, 0, $type);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
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

            DB::table($this->modelTable)->decrement('amount', $amount);
            DB::table($this->modelTable)->increment('frozen_amount', $amount);
            $this->addRecord($amount, 0, WALLET_TYPE_FROZEN);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
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

            DB::table($this->modelTable)->increment('amount', $amount);
            DB::table($this->modelTable)->decrement('frozen_amount', $amount);
            $this->addRecord($amount, 0, WALLET_TYPE_UNFROZEN);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
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

            DB::table($this->modelTable)->increment('amount', $amount);
            $this->addRecord($amount, 1, $type);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
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
}
