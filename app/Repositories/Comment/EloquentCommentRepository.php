<?php namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscribe\Preorder;
use App\Services\Comments\Event\CommentIsCreated;
use Mockery\Exception;

class EloquentCommentRepository implements CommentRepositoryContract {

    public function getAll($commentable_type, $order_by = 'created_at', $sort = 'desc')
    {
        return Comment::query()->has($this->getCommentAble($commentable_type))->orderBy($order_by, $sort)->get();
    }

    public function getAllPaginated($commentable_type, $order_by = 'created_at', $sort = 'desc', $per_page = 20)
    {
        return Comment::query()->has($this->getCommentAble($commentable_type))->orderBy($order_by, $sort)->paginate($per_page);
    }

    /**
     * @param $score
     * @param $content
     * @param $commentable_id
     * @param $commentable_type
     * @param array $image_ids
     * @return Comment
     * @throws \Exception
     */
    public function create($score, $content, $commentable_id, $commentable_type, $image_ids = [])
    {
        $comment = Comment::create([
            'score' => $score,
            'content' => $content,
        ]);

        try {
            $commentable = $this->getCommentAble($commentable_type);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            \Log::error($error);
            return $error;
        }

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
