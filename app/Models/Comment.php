<?php

namespace App\Models;

use App\Models\Order\Order;
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
        });
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

}
