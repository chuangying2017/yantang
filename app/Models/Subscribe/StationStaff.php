<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use Guzzle\Http\Client;
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

}