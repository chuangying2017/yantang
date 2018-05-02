<?php namespace App\Models\Collect;

use App\Models\Residence;
use App\Models\Collect\Address;
use App\Models\Product\ProductSku;
use App\Models\Order\Order;
use App\Models\Subscribe\StationStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectOrder extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'collect_orders';

    public function staff()
    {
        return $this->hasOne(StationStaff::class, 'id', 'staff_id')->withTrashed();
    }

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'id', 'sku_id')->withTrashed();
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public static function createOrder( $data ){
        return self::create($data);
    }

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id', 'id');
    }


    public static function getCollectOrderByOrderId( $order_id ){
        return self::where('order_id', $order_id)->first();
    }

    public static function getAddressIds(){
        return self::with('address')
                ->where('staff_id', access()->staffId())
                ->whereNotNull('order_id')
                ->groupBy('address_id')
                ->get();
    }
}
