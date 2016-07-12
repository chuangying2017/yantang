<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscribe\Preorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

    use SoftDeletes;

    protected $table = 'comments';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $comment) {
            $comment->images()->detach();
            $comment->products()->detach();
            $comment->orders()->detach();
            $comment->preorders()->detach();
        });
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'commentable');
    }

    public function orders()
    {
        return $this->morphedByMany(Order::class, 'commentable');
    }

    public function preorders()
    {
        return $this->morphedByMany(Preorder::class, 'commentable');
    }

}
