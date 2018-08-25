<?php

namespace App\Listeners\Integral;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ProductSku
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\Integral\productSku $event)
    {
        $product = $event->product;
        try{
            if ($product->product_sku)
            {
                $product->product_sku->browse_num += 1;
                $product->product_sku->save();
            }
        }catch (Exception $exception)
        {
            Log::error($exception->getMessage());
        }
    }
}
