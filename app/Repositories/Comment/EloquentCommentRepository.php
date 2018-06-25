<?php namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscribe\Preorder;
use App\Repositories\Page\RepoPageShare;
use App\Services\Comments\Event\CommentIsCreated;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Mockery\Exception;

class EloquentCommentRepository implements CommentRepositoryContract {
    use RepoPageShare;
    protected $pre_orders = ['station_id','staff_id','phone'];
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

            if(isset($data['CommentType']) && !empty($data['CommentType']) && $data['CommentType'] == CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL)
            {
                $find_result->additional_comments = $data['additionalComments'];

                $find_result->comment_type = CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL;

                $find_result->save();

                return $find_result;
            }

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

    public function getExpressionSelect($all, $updated_at = 'updated_at', $sort = 'desc', $paginate = 20)
    {

                $comment_Type = isset($all['commentType'])?[$all['commentType']]:[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_NOT_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL];

                $comment_data = Comment::whereIn('comment_type',$comment_Type);

                if(empty($all['other'])){
                    $comment_data->with('preorders','preorders.station','preorders.staff')->orderBy($updated_at,$sort);
                    return $comment_data->paginate($paginate);
                }

                $all['other']['page'] = $all['page'];

                $all = $this->fill($all['other']);

                if(isset($all['start_time']) && isset($all['end_time']) && strtotime($all['start_time']) < strtotime($all['end_time'])){
                    $comment_data->whereBetween('updated_at',[$all['start_time'],$all['end_time']]);
                }

                if(isset($all['start_time']) && empty($all['end_time'])){
                    $comment_data->where('updated_at','>',$all['start_time']);
                }

                if(isset($all['end_time']) && empty($all['start_time'])){
                    $comment_data->where('updated_at','<',$all['end_time']);
                }

                if(isset($all['score'])){
                    $comment_data->where('score',$all['score']);
                }

                $fetch_true_data = array_only($all,$this->pre_orders);


                if(!empty($fetch_true_data)){
                    $comment_data->whereHas('preorders',function ($query)use($fetch_true_data){
                        $query->where($fetch_true_data);
                    });
                }

                $comment_data->with('preorders.station','preorders.staff');

                if(isset($all['seniority'])){ // 判断 排名

                    $collect_data = $comment_data->get();

                    foreach ($collect_data as $key => $value)
                    {
                        $collect_data[$key]['staff_id'] = $value->preorders[0]['staff_id'];
                        $collect_data[$key]['station_id'] = $value->preorders[0]['station_id'];
                    }

                    $comment_data = $collect_data->groupBy('staff_id');

                    foreach ($comment_data as &$value)
                    {
                        $value['total_order'] = $value->count();

                        $value['have_comments_number'] = $value->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->count();

                        $value['scores'] = $value->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->avg('score');
                    }
                    $forPage = $comment_data->forPage($all['page']?:1,$paginate);

                    $result = $forPage->sortByDesc('scores')->values()->all();

                    return ['result'=>$result,'paging'=>['current_page'=>$all['page']?:1,'total_page'=>$forPage->count(),'paginate'=>$paginate]];

                }else{
                    $comment_data->orderBy($updated_at,$sort);
                }

               if($paginate){
                    $comment_data = $comment_data->paginate($paginate);
                }else{
                    $comment_data = $comment_data->get();
                }

                return $comment_data;
    }

    protected function fill($data)
    {
        return array_only($data,[
            'start_time',
            'end_time',
            'order_no',
            'score',
            'phone',
            'station_id',
            'staff_id',
            'page',
            'seniority',
        ]);
    }
}

