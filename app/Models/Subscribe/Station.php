<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use App\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'stations';

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
}
