<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;

class User extends Model
{

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_clients';

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
