<?php namespace App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
use App\Models\Subscribe\Preorder;
class StationInvoiceOrder extends Model{

    protected $table = 'invoice_orders';

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function preorder(){
        return $this->belongsTo(PreOrder::class, 'preorder_id', 'id');
    }

}
