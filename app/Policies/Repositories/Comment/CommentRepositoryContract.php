<?php namespace App\Repositories\Comment;
interface CommentRepositoryContract {

    public function getAll($commentable_type, $order_by = 'created_at', $sort = 'desc');

    public function getAllPaginated($commentable_type, $order_by = 'created_at', $sort = 'desc', $per_page = 20);

    public function create($score, $content, $commentable_id, $commentable_type, $image_ids = []);

    public function delete($comment_ids);

    public function summary();


}
