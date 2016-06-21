<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatementsProduct extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'statements_product';

    public function statements()
    {
        return $this->belongsTo(Statements::class);
    }

}
