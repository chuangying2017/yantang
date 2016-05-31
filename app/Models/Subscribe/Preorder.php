<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorders';

    public function user()
    {
        return $this->belongsTo('App\Models\Access\User\User');
    }
}
