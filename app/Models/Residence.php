<?php

namespace App\Models;

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

    public function getCompleteAttribute(){
        if(!$this->goal){
            return 0;
        }

        $residence_id = $this->id;

        //count preorder 已指派的
        $query = Preorder::query()
                    ->whereIn('status', [
                        PreorderProtocol::ORDER_STATUS_OF_SHIPPING,
                        PreorderProtocol::ORDER_STATUS_OF_DONE,
                    ])
                    ->where('residence_id', $residence_id)
                    ->distinct('user_id');

        $preorderAmount = $query->count('id');


        //count collect_order
        // $query = CollectOrder::query()
        //             ->whereIn('status', [
        //                 PostorderProtocol::STATUS_OF_PENDING,

        //             ])
        //             ->where('residence_id', $residence_id)
        //             ->distinct('user_id')
        // $postorderAmount  = $query->count('id');
        // dd($preorderAmount);
        //sum up
        return $preorderAmount/$this->goal;
    }

    public static function getResidenceIdByAddress( $address ){
        $residences = self::select(['id','name','aliases'])->get();

        $when = [];
        foreach( $residences as $residence ){
            $aliases = explode(',', $residence->aliases);
            foreach( $aliases as $alias ){
                $when[] = 'when "'.$address.'" LIKE "%'.$alias.'%" then "'.$residence->id."\"\n";
            }
        }
        if(!$residences){
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
