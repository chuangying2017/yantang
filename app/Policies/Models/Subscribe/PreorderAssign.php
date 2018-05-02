<?php

namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreorderAssign extends Model {

    use SoftDeletes;

    protected $table = 'preorder_assign';

    protected $guarded = [];

    protected $primaryKey = 'preorder_id';

    public $incrementing = false;

    public function preorder()
    {
        return $this->belongsTo(Preorder::class, 'preorder_id', 'id');
    }
}
