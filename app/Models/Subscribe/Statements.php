<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Statements extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'statements';

    public function product()
    {
        return $this->hasMany(StatementsProduct::class);
    }

}
