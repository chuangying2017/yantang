<?php

namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderDeliver extends Model {

    protected $guarded = ['id'];

    protected $table = 'preorder_deliver';

    public function skus()
    {
        return $this->belongsToMany(PreorderSku::class, 'deliver_preorder_skus', 'preorder_deliver_id', 'preorder_sku_id')->withPivot(['quantity']);
    }

    public function preorder()
    {
        return $this->belongsTo(Preorder::class, 'preorder_id', 'id');
    }

}
