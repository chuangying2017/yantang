<?php

namespace App\Api\V1\Controllers\Admin\Subscribe;

use App\Api\V1\Controllers\Client\CommentControllerAbstract;
use App\Models\Subscribe\Preorder;
use App\Http\Requests;

class CommentController extends CommentControllerAbstract {

    public function setType()
    {
        $this->commentable_type = Preorder::class;
    }

}
