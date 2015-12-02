<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Comment;


class CommentServce
{
    public static function create($data)
    {
        return CommentsRepository::create($data);
    }

    public static function delete($id)
    {
        return CommentsRepository::delete($id);
    }

    //todo@bryant: add product sku to comment
    /**
     * get a comment by id
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        $comment = Comment::with('user.avatar', 'user.username', 'images')->where('id', $id)->get();
        return $comment;
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
