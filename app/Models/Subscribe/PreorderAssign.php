<?php

namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderAssign extends Model {

    protected $table = 'preorder_assign';

    protected $guarded = [];

    protected $primaryKey = 'preorder_id';

    public function preorder()
    {
        return $this->belongsTo(Preorder::class, 'preorder_id', 'id');
    }
}
