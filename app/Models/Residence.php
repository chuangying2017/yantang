<?php

namespace App\Models;

use App\Models\District;
use App\Models\Order\Order;
use App\Models\Collect\CollectOrder;
use App\Models\Subscribe\Preorder;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DB;

class Residence extends Model
{
    use SoftDeletes;

    protected $table = 'residences';

    protected $guarded = [];

    public function district(){
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function getCompleteAttribute(){
        $residence_id = $this->id;

        $collecOrderTableName = with(new CollectOrder)->getTable();
        $orderTableName = with(new Order)->getTable();
        $preorderTableName = with(new Preorder)->getTable();

        //preorder 已指派的
        $preordersUid = Preorder::query()
                    ->whereIn('status', [
                        PreorderProtocol::ORDER_STATUS_OF_SHIPPING,
                        PreorderProtocol::ORDER_STATUS_OF_DONE,
                    ])
                    ->where('residence_id', $residence_id)
                    ->distinct('user_id')
                    ->pluck('user_id');

        //collect_order
        $collectOrdersUid = CollectOrder::query()
                    ->where('residence_id', $residence_id)
                    ->whereNotNull($collecOrderTableName.'.pay_at')
                    ->leftJoin('orders', $orderTableName.'.id', '=', $collecOrderTableName.'.order_id')
                    ->distinct($orderTableName.'.user_id')
                    ->pluck('user_id');

        $uids = array_merge($preordersUid->toArray(),$collectOrdersUid->toArray());
        $amount = count(array_unique($uids));

        //sum up
        return $amount;
    }

    public static function getResidenceIdByAddress( $address, $district_id ){
        $residences = self::where('district_id',$district_id)->select(['id','name','aliases'])->get();

        $when = [];
        foreach( $residences as $residence ){
            $aliases = explode(',', $residence->aliases);
            foreach( $aliases as $alias ){
                $when[] = 'when "'.$address.'" LIKE "%'.$alias.'%" then "'.$residence->id."\"\n";
            }
        }
        if($residences->isEmpty()){
            \Log::error('No residence found in table.');
            return null;
        }

        $case = DB::raw('case '.implode(' ', $when).' end as id');
        $residence = DB::select('select '.$case);
        if(!$residence){
            \Log::error('No residence match '.$address.'.');
            return null;
        }
        $residence = array_shift($residence);
        if(is_null($residence->id) ){
            \Log::error('no match '. $address);
        }
        return $residence->id;
    }
}
