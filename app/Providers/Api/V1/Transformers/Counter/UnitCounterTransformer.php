<?php namespace App\Api\V1\Transformers\Counter;

use App\Models\Counter\UnitCounter;
use League\Fractal\TransformerAbstract;

class UnitCounterTransformer extends TransformerAbstract {

    public function transform(UnitCounter $counter)
    {
        return [
            'date' => $counter['time'],
            'quantity' => $counter['quantity'],
            'amount' => display_price($counter['amount']),
        ];
    }

}
