<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;

class LabelSet extends Model
{
    //
    protected $table = 'labelset';

    public $timestamps = false;

    protected $fillable = ['title','star_level'];
}
