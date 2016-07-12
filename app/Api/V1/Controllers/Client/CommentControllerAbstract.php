<?php

namespace App\Api\V1\Controllers\Client;

use App\Api\V1\Transformers\CommentTransformer;
use App\Repositories\Comment\CommentRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

abstract class CommentControllerAbstract extends Controller {

    /**
     * @var CommentRepositoryContract
     */
    protected $commentRepo;

    protected $commentable_type;

    /**
     * CommentControllerAbstract constructor.
     * @param CommentRepositoryContract $commentRepo
     */
    public function __construct(CommentRepositoryContract $commentRepo)
    {
        $this->commentRepo = $commentRepo;
        $this->setType();
    }

    public abstract function setType();

    protected function getType()
    {
        return $this->commentable_type;
    }

    public function index(Request $request)
    {
        $order_by = $request->input('order_by');
        $sort = $request->input('sort');
        $comments = $this->commentRepo->getAllPaginated($this->getType(), $order_by, $sort);

        return $this->response->paginator($comments, new CommentTransformer());
    }

    public function store(Request $request)
    {
        $score = $request->input('score');
        $content = $request->input('content');
        $commentable_id = $request->input('commentable_id');

        $comment = $this->commentRepo->create($score, $content, $commentable_id, $this->getType());

        return $this->response->item($comment, new CommentTransformer())->setStatusCode(201);
    }

    public function destroy($comment_id)
    {
        $this->commentRepo->delete($comment_id);

        return $this->response->noContent();
    }
}
