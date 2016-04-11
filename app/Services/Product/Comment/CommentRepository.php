<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */

namespace App\Services\Product\Comment;


use App\Models\Comment;
use Pheanstalk\Exception;

/**
 * Class CommentsRepository
 * @package App\Services\Product\Comment
 */
class CommentsRepository
{
    /**
     * create a comment
     * @param $data
     *  - (string) content : 评论内容
     *  - (integer) user_id
     *  - (integer) product_id
     *  - (integer) order_id
     *  - (bool) status : 评论状态, 1 为 正常, 0 为不显示
     *  - (array) image_ids: 图片id
     * @return static
     */
    public static function create($data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['image_ids'])) {
                $image_ids = $data['image_ids'];
            }

            $data = array_only($data, ['content', 'user_id', 'product_id', 'order_id']);

            $record = Comment::where('user_id', $data['user_id'])->where('order_id', $data['order_id'])->count();

            if ($record > 0) {
                throw new Exception('user already commented for the order');
            }

            $comment = Comment::create($data);

            if (count($image_ids) > 0) {
                $comment->images()->attach($image_ids);
            }

            return $comment;

            DB::commit();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $data
     * @return int|string
     */
    public static function update($id, $data)
    {
        try {
            $comment = Comment::find($id);
            if (!$comment) {
                throw new Exception("COMMENT NOT FOUND");
            }
            $data = array_only($data, ['status', 'content']);
            $comment->update($data);

            return 1;
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return bool
     */
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
