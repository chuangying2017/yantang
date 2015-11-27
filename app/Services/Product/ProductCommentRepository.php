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
    /**
     * create a comment
     * @param $data
     */
    public static function create($data)
    {
        try {
            DB::beginTransaction();

            $comment = new Comment;

            $comment->content = $data['content'];
            $comment->user_id = $data['user_id'];
            $comment->product_id = $data['product_id'];
            $comment->order_id = $data['order_id'];
            $comment->save();

            if (count($data['image_ids']) > 0) {
                $comment->images()->attach($data['image_ids']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $comment = Comment::findOrFail($id);
            $comment->delete();

            $comment->images()->detach();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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
    public static function getByProductId($product_id)
    {
        $comments = Comment::with('user.avatar', 'user.username', 'images')->where('product_id', $product_id)->where('status', 1)->get();
        return $comments;
    }
}
