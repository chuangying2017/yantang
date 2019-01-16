<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use App\Models\Counter\Counter;
use App\Models\District;
use App\Repositories\Counter\CounterProtocol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'stations';
    //站点细信息
    public function setGeoAttribute($geo)
    {
        if (is_array($geo)) {
            $this->attributes['geo'] = json_encode($geo);
        } else {
            $this->attributes['geo'] = $geo;
        }
    }

    public function getGeoAttribute()
    {
        return json_decode($this->attributes['geo'], true);
    }

    public function preorder()
    {
        return $this->hasMany(Preorder::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'station_user', 'station_id', 'user_id');
    }

    public function staffs()
    {
        return $this->hasMany(StationStaff::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function counter()
    {
        return $this->hasOne(Counter::class, 'source_id', 'id')->where('source_type', CounterProtocol::COUNTER_TYPE_OF_STATION_PREORDER);
    }
}
