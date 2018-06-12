<?php

namespace App\Api\V1\Controllers\Comments;

use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Repositories\setting\SetMode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    //
    protected $set_mode;

    protected $contract;

    protected $comment_contract;

    public function __construct(SetMode $setMode,PreorderRepositoryContract $contract,CommentRepositoryContract $commentRepositoryContract)
    {
        $this->set_mode = $setMode;
        $this->contract = $contract;
        $this->comment_contract = $commentRepositoryContract;
    }

    //show setting star level content
    public function show($id, Request $request)
    {

        try {

            $result=$request->input('preorderId',null);

            return $this->response->array([
                'settingArray'=>$this->set_mode->getSetting($id),
                'preorders'=>$result?$this->comment_contract->get($result):$result
            ]);
        } catch (\ErrorException $e) {
            Log::error($e->getMessage());
        }

    }

    public function update($comment_id, Request $request)//patch request
    {
        try{

            $comment_update_result = $this->comment_contract->update_comment($comment_id,$request->all());

            $this->response->array($comment_update_result);

            }catch (\Exception $exception){

            }
    }
}
