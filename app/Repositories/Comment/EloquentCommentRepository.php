<?php namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscribe\Preorder;
use App\Services\Comments\Event\CommentIsCreated;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
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

    public function getExpressionSelect($all, $updated_at = 'updated_at', $sort = 'desc', $paginate = 20)
    {

                $comment_data = Comment::query()->where('comment_type',CommentProtocol::COMMENT_STATUS_IS_USES);

                if(empty($all['other'])){
                    $comment_data->with('preorders','preorders.station','preorders.staff');
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

                if(isset($all['seniority'])){

                    $groupBy = '';
                    if(isset($all['phone']) && strlen($all['phone']) == 11){
                        $comment_data->whereHas('preorders',function($query)use($all){
                            $query->where('phone',$all['phone']);
                        });
                        empty($groupBy)?$groupBy = 'phone':$groupBy[]='phone';
                    }

                    if(isset($all['station_id'])){
                        $comment_data->whereHas('preorders',function ($query)use($all){
                            $query->where('station_id',$all['station_id']);
                        });
                         empty($groupBy)?$groupBy ='station_id':$groupBy[]='station_id';
                    }

                    $comment_data->whereHas('preorders',function($query)use($all,$groupBy){

                        if(isset($all['staff_id'])){

                            empty($groupBy)?$groupBy ='staff_id':$groupBy[]='staff_id';

                            $query->where('staff_id',$all['staff_id'])->groupBy($groupBy);
                        }else{
                            $query->groupBy(isset($groupBy)&&!empty($groupBy)?$groupBy:['staff_id','station_id']);
                        }

                    });

                    $comment_data->selectRaw('avg(score) as scores,id,score,content,comment_type,comment_label,updated_at');

                    $updated_at = 'scores';
                }else{

                    if(isset($all['phone']) && strlen($all['phone']) == 11){
                        $comment_data->whereHas('preorders',function($query)use($all){
                            $query->where('phone',$all['phone']);
                        });
                    }

                    if(isset($all['station_id'])){
                        $comment_data->whereHas('preorders',function ($query)use($all){
                            $query->where('station_id',$all['station_id']);
                        });
                    }

                    if(isset($all['staff_id'])){
                        $comment_data->whereHas('preorders',function($query)use($all){
                            $query->where('staff_id',$all['staff_id']);
                        });
                    }

                }

                $comment_data->with('preorders.station','preorders.staff');

                $comment_data->orderBy($updated_at,$sort);
          
                if(isset($all['seniority'])){
                    $comment_data = $comment_data->paginate($paginate);
                }elseif($paginate){
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

