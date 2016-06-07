<?php namespace App\Services\Order;

use App\Repositories\Cart\CartRepositoryContract;

class CartOrderGenerator {

    /**
     * @var CartRepositoryContract
     */
    private $cartRepo;
    /**
     * @var OrderGeneratorContract
     */
    private $orderGenerator;

    /**
     * CartOrderGenerator constructor.
     * @param CartRepositoryContract $cartRepo
     */
    public function __construct(CartRepositoryContract $cartRepo, OrderGeneratorContract $orderGenerator)
    {
        $this->cartRepo = $cartRepo;
        $this->orderGenerator = $orderGenerator;
    }

    public function buyFromCart($user_id, $cart_ids, $address)
    {
        $carts = $this->cartRepo->getMany($cart_ids, false);

        $order = $this->orderGenerator->buy($user_id, $carts->only(['product_sku_id', 'quantity']));

        //删除已购买购物车信息
    }

}
