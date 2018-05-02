<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationStaff extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'station_staffs';

    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id', 'user_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    public function preorders()
    {
        return $this->hasMany(Preorder::class, 'staff_id', 'id');
    }

}
