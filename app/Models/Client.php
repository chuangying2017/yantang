<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{

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
    protected $guarded = ['id'];


    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet');
    }

    public function creditsWallet()
    {
        return $this->hasOne('App\Models\CreditsWallet');
    }
}
