<?php namespace App\Models\Invoice;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class StationInvoiceCollectOrder extends Model{

    protected $table = 'invoice_collect_orders';

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
