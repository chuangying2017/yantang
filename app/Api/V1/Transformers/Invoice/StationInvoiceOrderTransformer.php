<?php namespace App\API\V1\Transformers\Invoice;
use App\Models\Invoice\StationInvoiceOrder;
use League\Fractal\TransformerAbstract;

class StationInvoiceOrderTransformer extends TransformerAbstract{

    public function transform(StationInvoiceOrder $order)
    {
        return $order->toArray();
    }
    
}
