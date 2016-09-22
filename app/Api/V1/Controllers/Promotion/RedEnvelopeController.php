<?php

namespace App\Api\V1\Controllers\Promotion;

use App\API\V1\Transformers\Promotion\RedEnvelopeReceiveTransformer;
use App\API\V1\Transformers\Promotion\RedEnvelopeRecordTransformer;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\RedEnvelope\RedEnvelopeRecordRepository;
use App\Services\Promotion\RedEnvelopeService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RedEnvelopeController extends Controller {

    /**
     * @var RedEnvelopeRecordRepository
     */
    private $recordRepo;

    /**
     * RedEnvelopeController constructor.
     * @param RedEnvelopeRecordRepository $recordRepo
     */
    public function __construct(RedEnvelopeRecordRepository $recordRepo)
    {
        $this->recordRepo = $recordRepo;
    }

    public function show($record_id)
    {
        $record = $this->recordRepo->get($record_id, true);

        return $this->response->item($record, new RedEnvelopeRecordTransformer());
    }

    public function store(Request $request, RedEnvelopeService $redEnvelopeService, EloquentUserRepository $userRepository)
    {
        $record_id = $request->input('record');

        $user = access()->user();
        $receive = $redEnvelopeService->dispatch($userRepository->setUser($user), $record_id);

        if (!$receive) {
            $this->response->accepted(null, '活动已结束或抢完了');
        }

        return $this->response->item($receive, new RedEnvelopeReceiveTransformer())->setStatusCode(201);
    }


}
