<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Client\CommentControllerAbstract;

use App\Http\Requests;
use App\Models\Subscribe\Preorder;

class PreorderCommentController extends CommentControllerAbstract {

    public function setType()
    {
        $this->commentable_type = Preorder::class;
    }

}
