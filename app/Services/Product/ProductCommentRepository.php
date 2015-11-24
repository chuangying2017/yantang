<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */

namespace App\Services\Product;


use App\Models\Comment;

class ProductCommentsRepository
{
    public static function create($data)
    {
        $comment = new Comment;

        $comment->message = $data['message'];
        $comment->user_id = $data['user_id'];
        $comment->product_id = $data['product_id'];
        $comment->save();
    }

    public static function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }

    public static function getById($id)
    {
        return Comment::findOrFail($id);
    }

    public static function getByProductId($product_id)
    {
        return Comment::where('product_id', $product_id)->get();
    }
}
