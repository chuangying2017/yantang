<?php

namespace App\Console\Commands;

use App\Models\Product\AttributeValue;
use App\Models\Order\OrderSku;
use App\Models\Product\ProductSku;
use Illuminate\Console\Command;

class ProductUpdateAttribute extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-attribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $skus = ProductSku::get();
        foreach ($skus as $sku) {
            $attribute_values = $sku->attributeValues()->get();
            $attribute_values->load('attribute');
            $attributes = [];
            foreach ($attribute_values as $attribute_value) {
                $attributes[] = [
                    'attribute_value_id'   => $attribute_value['id'],
                    'attribute_value_name' => $attribute_value['value'],
                    'attribute_id'         => $attribute_value['attribute']['id'],
                    'attribute_name'       => $attribute_value['attribute']['name'],
                ];
            }

            $sku->attributes = json_encode($attributes);
            $sku->save();
        }

        $order_products = OrderSku::with('product')->get();
        foreach ($order_products as $order_product) {
            if (isset($order_product->product->attributes)) {
                $order_product->attributes = $order_product->product->attributes;
                $order_product->save();
            }
        }
    }
}
