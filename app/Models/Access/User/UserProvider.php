<?php namespace App\Models\Access\User;

use App\Models\Integral\IntegralRecord;
use App\Models\Integral\SignMonthModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProvider
 * @package App\Models\Access\User
 */
class UserProvider extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_providers';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function SignMonth()
    {
        return $this->hasMany(SignMonthModel::class,'user_id','user_id');
    }

    public function integralRecord()
    {
        return $this->hasMany(IntegralRecord::class,'user_id','user_id');
    }
}
