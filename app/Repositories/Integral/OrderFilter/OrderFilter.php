<?php
namespace App\Repositories\Integral\OrderFilter;

use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Repositories\Common\config\DispatchClass;
use App\Repositories\Integral\OrderGenerator\OrderIntegral;
use App\Repositories\Integral\OrderGenerator\OrderIntegralAddress;
use App\Repositories\Integral\OrderGenerator\OrderIntegralSku;
use App\Services\Client\Account\AccountProtocol;
use App\Services\Integral\Product\ProductInerface;

class OrderFilter
{

    protected $interfaceClass;

    protected $product;

    protected $wallet;

    protected $data;

    protected $user_id;

    public function __construct(ProductInerface $productInerface, EloquentWalletRepository $eloquentWalletRepository)
    {
        $this->interfaceClass   =   $productInerface;
        $this->wallet           =   $eloquentWalletRepository;
    }

    public function filter()
    {

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
        $this->product = $this->interfaceClass->get_product($id,false);
        return $this;
    }

    protected function settle_accounts()
    {
        return $this->product['integral'] * $this->data['buy_num'];
    }

    /**
     * @return array|bool
     */
    public function user_compare_integral() // 比较用户与下单产品的积分
    {
        $boolean = AccountProtocol::ACCOUNT_NOT_INTEGRAL;

        if($this->settle_accounts()
            >=
            $this
                ->wallet
                ->setUserId($this->user_id)
                ->getAmount(AccountProtocol::ACCOUNT_AMOUNT_INTEGRAL))
        {
                $boolean = true;
        }

        return $boolean;

    }

    public function set_user_Id($id)
    {
        $this->user_id = $id;
        return $this;
    }

    public function array_filterMode(array $array)
    {
        return array_only($array,[
            'address',
            'product_id',
            'buy_num',
            'product_name',
            'product_integral',
        ]);
    }

    public function order_production()
    {
        $handle = DispatchClass::get_container($this->handle_config());

        \DB::transaction(function ()use ($handle) {
           return $handle->handle($this->data);
        });
    }

    protected function handle_config()
    {
        return [
            OrderIntegral::class,
            OrderIntegralAddress::class,
            OrderIntegralSku::class,
        ];
    }
}