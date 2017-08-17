<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DB;

class Residence extends Model
{
    use SoftDeletes;

    protected $table = 'residences';

    protected $guarded = [];

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
