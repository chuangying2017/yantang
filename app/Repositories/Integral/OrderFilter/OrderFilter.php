<?php
namespace App\Repositories\Integral\OrderFilter;

use App\Models\Integral\Product;
use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Services\Client\Account\AccountProtocol;
use App\Services\Integral\Product\ProductInerface;
use App\Services\Integral\Product\ProductProtocol;
use Mockery\Exception;

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

    public function filter($data)
    {


    }

    public function index($data)
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

    public function user_compare_integral()
    {
        $boolean = false;

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
}