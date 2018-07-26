<?php
namespace App\Repositories\Integral\OrderFilter;

use App\Models\Integral\IntegralOrder;
use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Repositories\Common\config\DispatchClass;
use App\Repositories\Integral\OrderGenerator\OrderIntegral;
use App\Repositories\Integral\OrderGenerator\OrderIntegralAddress;
use App\Repositories\Integral\OrderGenerator\OrderIntegralSku;
use App\Services\Client\Account\AccountProtocol;
use App\Services\Integral\Product\ProductInerface;
use Carbon\Carbon;

class OrderFilter
{

    protected $interfaceClass;

    protected $product;

    protected $product_sku;

    protected $wallet;

    public $data;

    protected $user_id;

    public function __construct(ProductInerface $productInerface, EloquentWalletRepository $eloquentWalletRepository)
    {
        $this->interfaceClass   =   $productInerface;
        $this->wallet           =   $eloquentWalletRepository;
    }

    public function index(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function get_product_current_integral()
    {
        return $this->product['integral'];
    }

    public function set_product($id)
    {
        $pk = $this->interfaceClass->get_product($id,false);

        $this->product = $pk;

        $this->product_sku = $pk->product_sku;

        return $this;
    }

    public function settle_accounts()
    {
        return $this->product['integral'] * $this->data['buy_num'];
    }

    /**
     * @return array|bool
     */
    public function user_compare_integral() // 比较用户与下单产品的积分
    {

       switch (true)
       {
           case $this->settle_accounts()
               >
               $this
                   ->wallet
                   ->setUserId($this->user_id)
                   ->getAmount(AccountProtocol::ACCOUNT_AMOUNT_INTEGRAL):
               $boolean = AccountProtocol::ACCOUNT_NOT_INTEGRAL;
           break;
           case $this->data['buy_num'] > $this->product_sku->convert_unit:
               $boolean = '兑换份数不能大于'. $this->product_sku->convert_unit . '份！';
               break;
           case $this->product_sku->remainder < $this->data['buy_num']:
               $boolean = AccountProtocol::ACCOUNT_PRODUCT_ENOUGH;
               break;
           case $this->judge_dot() >= $this->product_sku->convert_num:
               $boolean = '兑换超过了'. $this->product_sku->convert_num .'次！';
               break;
           default:
               $boolean = true;
               break;
       }

        return $boolean;

    }

    public function judge_dot()//对比下单次数
    {
        $order = IntegralOrder::where('user_id',$this->user_id)
            ->whereBetween('created_at',[Carbon::today(),Carbon::today()
                ->addDays(-$this->product_sku->convert_day)])->count();

        return (int)$order;
    }
    public function set_user_Id($id)
    {
        $this->user_id = $id;
        return $this;
    }

    public function order_production()
    {
        $handle = DispatchClass::get_container($this->handle_config());

        $order = \DB::transaction(function ()use ($handle) {
           return $handle->handle($this->data,new IntegralOrder());
        });

        $this->deduction_integral_user();

        $this->account_product();

        return $order;
    }

    protected function handle_config()
    {
        return [
            OrderIntegral::class,
            OrderIntegralAddress::class,
            OrderIntegralSku::class,
        ];
    }

    public function deduction_integral_user()
    {
        \DB::beginTransaction();

        $this->wallet->setUserId($this->user_id)->getAccount()->decrement('integral',$this->settle_accounts());

        \DB::commit();
    }

    public function set_data_value()
    {
        $this->data = merge_array(
            $this->data,
            [
                'cost_integral' => $this->settle_accounts(),
                'user_id' => $this->user_id,
                'product_integral' => $this->product['integral'],
                'product_name' => $this->product_sku->name,
                'postage' => $this->product->postage ?: '0.00',
            ]
        );
    }

    public function account_product()
    {
        $product_sku = $this->product_sku;

        $product_sku->remainder -= $this->data['buy_num'];

        $product_sku->sales += $this->data['buy_num'];

        $product_sku->save();
    }
}