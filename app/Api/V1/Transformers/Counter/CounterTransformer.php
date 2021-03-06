<?php namespace App\Api\V1\Transformers\Counter;

use App\Models\Counter\Counter;
use League\Fractal\TransformerAbstract;

class CounterTransformer extends TransformerAbstract {

    public function transform(Counter $counter)
    {
        return [
            'name' => $counter['source_name'],
            'quantity' => $counter['quantity'],
            'amount' => display_price($counter['amount']),
            'user_count' => $counter['user_count'],
            'user_count_kpi' => $counter['user_count_kpi'],
        ];
    }

}
