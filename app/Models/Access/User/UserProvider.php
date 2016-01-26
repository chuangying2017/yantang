<?php namespace App\Models\Access\User;

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
}
