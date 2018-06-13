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
        }
        file_put_contents('commentAble.txt',$commentable);
        $comment->$commentable()->attach($commentable_id);

        if (count($image_ids)) {
            $comment->images()->sync($image_ids);
        }

       // event(new CommentIsCreated($comment));

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

    public function update_comment($comment_id,$data)
    {
        // comment_type field type: (ToBeUsed expression not uses HaveUses on uses)
        try{
            $find_result = Comment::find($comment_id);

            $find_result->fill($data);

            $find_result->comment_type = CommentProtocol::COMMENT_STATUS_IS_USES;

            $find_result->save();

            if(!isset($find_result->id))
                throw  new \Exception('comment update failed');

            event(new CommentIsCreated($find_result));

            return $find_result;

        }catch (Exception $exception){
            return $exception->getMessage();
        }

    }

    public function get($order_id){//uses comment fetch show by client front-end

        $preorder_data = Preorder::query()->findOrFail($order_id);

        if($preorder_data){
            $preorder_data->load('skus', 'station', 'skus.sku', 'staff', 'order');
        }

        return $preorder_data;
    }
}

