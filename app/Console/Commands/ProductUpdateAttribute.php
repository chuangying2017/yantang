<?php

namespace App\Console\Commands;

use App\Models\AttributeValue;
use App\Models\ProductSku;
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
            $attribute_values = AttributeValue::with('attribute')->whereIn('id', [1, 5])->get();
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
    }
}
