<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Comment;


/**
 * Class CommentServce
 * @package App\Services\Product\Comment
 */
use App\Models\Comment;

/**
 * Class CommentServce
 * @package App\Services\Product\Comment
 */
class CommentServce
{
    /**
     * @param $data
     * @return static
     */
    public static function create($data)
    {
        return CommentsRepository::create($data);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return CommentsRepository::delete($id);
    }

    /**
     * @param $id
     * @return int|string
     */
    public static function block($id)
    {
        return CommentsRepository::update($id, ['status' => 0]);
    }

    /**
     * @param $id
     * @return int|string
     */
    public static function unblock($id)
    {
        return CommentsRepository::update($id, ['status' => 1]);
    }

    /**
     * get comments by product
     * @param $product_id
     * @return mixed
     */
    public static function getByProduct($product_id)
    {
        $comments = Comment::with('user.avatar', 'user.username', 'images')->where('product_id', $product_id)->where('status', 1)->get();
        return $comments;
    }
}
