<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model {

    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'images';

    protected $primaryKey = 'media_id';

    public function products()
    {
        return $this->morphedByMany(Product::class, 'imageable');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'imageable');
    }
}
