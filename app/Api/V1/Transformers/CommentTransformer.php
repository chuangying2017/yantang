<?php namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['images'];

    public function transform(Comment $comment)
    {
        $this->setInclude($comment);

        return [
            'id' => $comment['id'],
            'score' => (int)$comment['score'],
            'content' => $comment['content'],
            'comment_label' => $comment['comment_label'],
            'comment_type' => $comment['comment_type'],
        ];
    }

    public function includeImages(Comment $comment)
    {
        return $this->collection($comment->images, new ImageTransformer(), true);
    }

}
