<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */

namespace App\Services\Product\Comment;


use App\Models\Comment;

class CommentsRepository
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
}
