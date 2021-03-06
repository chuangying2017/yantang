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
    protected $pre_orders = ['station_id','staff_id','phone','order_no'];
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

                $comment_Type = isset($all['other']['commentType'])?[$all['other']['commentType']]:[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_NOT_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL];

                $comment_data = Comment::whereIn('comment_type',$comment_Type);

                if(empty($all['other'])){
                    $comment_data->with('preorders','preorders.station','preorders.staff')->orderBy($updated_at,$sort);
                    return $comment_data->paginate($paginate);
                }

                $all['other']['page'] = $all['page'];

                $all = $this->fill($all['other']);

                if(isset($all['start_time']) && isset($all['end_time']) && strtotime($all['start_time']) <= strtotime($all['end_time'])){
                    $comment_data->whereBetween('updated_at',[$all['start_time'],$all['end_time']]);
                }
                /*if(isset($all['start_time']) && isset($all['end_time']) && strtotime($all['start_time']) > strtotime($all['end_time'])){
                    throw new \Exception('start time cannot gt end time',500);
                }*/

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

                    $comment_data = $collect_data->groupBy(isset($all['type_role'])?$all['type_role']:'staff_id');// Ranking by information

                    if(isset($all['staff_role'])){
                        return $this->staff_show_comment_ranking($comment_data,$all,$paginate);
                    }

                    $comment_data = $this->comment_data($comment_data);

                    $result = $comment_data->sortByDesc('scores');

                    if(isset($all['station_ranking'])){
                        $j = 1;
                        foreach ($result as $keys=>&$item){

                            $item['MilkMan'] = $this->comment_data($item->groupBy('staff_id'),true)->sortByDesc('scores')->values()->all();
                            $item['ranking'] = $j;
                            $item['ranking_id'] = $keys;

                            ++$j;
                        }

                        return $result->where('ranking_id',$all['station_ranking'])->values()->all();
                    }

                    $result = $result->forPage($all['page']?:1,$paginate)->values()->all();

                    $count_ = $comment_data->count();

                    return ['result'=>$result,'paging'=>['current_page'=>$all['page']?:1,'total'=>$count_,'per_page'=>$paginate,'total_pages'=>ceil($count_ / $paginate)]];

                }

                $comment_data->orderBy($updated_at,$sort);

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
            'type_role',
            'station_ranking',
            'staff_role',
            'ranking_id',
        ]);
    }

    public function comment_data($comment_data,$staff_name = false)
    {
        foreach ($comment_data as $key=>&$value)
        {

            $condition = $value->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->count();

            if($condition){

                $value['total_order'] = $value->count();

                $value['have_comments_number'] = $value->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->count();

                $value['scores'] = $value->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->avg('score');

                $staff_name?$value['staff_name'] = $value->first()->preorders->first()->staff->name:false;

                continue;
            }

            $comment_data->forget($key);
        }

        return $comment_data;
    }

    public function station_data_dispose($data)//站点数据处理
    {
        if(!isset($data['staff_id']) && !isset($data['page'])){
            $data['seniority'] = 1;
            $data['station_ranking'] = access()->stationId();
            $data['type_role'] = 'station_id';
        }

        $array['other'] = $data;
        $array['page'] = isset($data['page'])?$data['page']:1;

        return $this->getExpressionSelect($array);
    }

    public function staff_show_comment_ranking($comment_data,$all,$paginate)
    {
            if(!$comment_data->count()){
                return [];
            }
            $j=0;
            foreach ($comment_data as $keys => $datum)
            {
                $condition = $datum->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->count();

                if(!$condition){
                    continue;
                }

                $arr[$j]['total_order'] = $condition;

                $arr[$j]['have_comments_number'] = $datum->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->count();

                $arr[$j]['scores'] = $datum->whereIn('comment_type',[CommentProtocol::COMMENT_STATUS_IS_USES,CommentProtocol::COMMENT_STATUS_IS_ADDITIONAL])->avg('score');

                $arr[$j]['ranking_id'] = $datum->first()->staff_id;
                //$arr[]['name_staff'] = $datum->first()->preorders->first()->staff->name;
                ++$j;
            }
            $coll = collect($arr)->sortByDesc('scores')->values();

            $where = $coll->where('ranking_id',$all['ranking_id'])->all();

            if(empty($where)){
                return [];
            }

            $key = key($where) + 1;

            $where[$key-1]['ranking'] = $key;

            $array = array_values($where);

            $staff = $comment_data[$all['ranking_id']];//获取送奶工所有的数据

            $count_ = $staff->count();

            return [
                'data'=>$staff->forPage($all['page'],$paginate)->values()->all(),
                'pagination'=>['current_page'=>$all['page'],'total_data'=>$count_,'per_page'=>$paginate,'total_pages'=>ceil($count_ / $paginate)],
                'staff' => $array,
            ];
    }
}

