<?php namespace App\Repositories\Comment;

use App\Models\Comment;

class EloquentCommentRepository implements CommentRepositoryContract {

    public function getAll($commentable_type, $order_by = 'created_at', $sort = 'desc')
    {
        return Comment::query()->where('commentable_type', $commentable_type)->orderBy($order_by, $sort)->get();
    }

    public function getAllPaginated($commentable_type, $order_by = 'created_at', $sort = 'desc', $per_page = 20)
    {
        return Comment::where('commentable_type', $commentable_type)->orderBy($order_by, $sort)->paginate($per_page);
    }

    public function create($score, $content, $commentable_id, $commentable_type, $image_ids = [])
    {
        $comment = Comment::create([
            'score' => $score,
            'content' => $content,
            'commentable_id' => $commentable_id,
            'commentable_type' => $commentable_type
        ]);

        if (count($image_ids)) {
            $comment->images()->sync($image_ids);
        }

        return $comment;
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
