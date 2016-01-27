<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {

    protected $table = 'channels';

    protected $guarded = ['id'];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'channel_brand', 'channel_id', 'brand_id')->withPivot('brand_id', 'channel_id');
    }
}
