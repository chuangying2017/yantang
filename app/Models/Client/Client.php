<?php

namespace App\Models\Client;

use App\Models\Access\User\UserProvider;
use App\Models\Client\Account\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'user_id', 'id');
    }

    public function providers()
    {
        return $this->hasMany(UserProvider::class, 'user_id', 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class,'user_id','user_id');
    }
}
