<?php namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscribe\Preorder;
use App\Services\Comments\Event\CommentIsCreated;

class EloquentCommentRepository implements CommentRepositoryContract {

    public function getAll($commentable_type, $order_by = 'created_at', $sort = 'desc')
    {
        return Comment::query()->has($this->getCommentAble($commentable_type))->orderBy($order_by, $sort)->get();
    }

    public function getAllPaginated($commentable_type, $order_by = 'created_at', $sort = 'desc', $per_page = 20)
    {
        return Comment::query()->has($this->getCommentAble($commentable_type))->orderBy($order_by, $sort)->paginate($per_page);
    }

    public function create($score, $content, $commentable_id, $commentable_type, $image_ids = [])
    {
        $comment = Comment::create([
            'score' => $score,
            'content' => $content,
        ]);

        $commentable = $this->getCommentAble($commentable_type);
        $comment->$commentable()->attach($commentable_id);

        if (count($image_ids)) {
            $comment->images()->sync($image_ids);
        }

        event(new CommentIsCreated($comment));

        return $comment;
    }

    protected function getCommentAble($commentable_type)
    {
        switch ($commentable_type) {
            case Product::class:
                return 'products';
            case Order::class:
                return 'orders';
            case Preorder::class:
                return 'preorders';
        }

        throw new \Exception('评论主体错误');
    }

    public function delete($comment_ids)
    {
        return Comment::destroy($comment_ids);
    }

    public function summary()
    {
        // TODO: Implement summary() method.
    }
}
