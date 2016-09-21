<?php

namespace App\Api\V1\Controllers\Admin\Promotion;

use App\Api\V1\Requests\Promotion\RedEnvelopeRuleRequest;
use App\API\V1\Transformers\Admin\Promotion\RedEnvelopeRuleTransformer;
use App\Repositories\RedEnvelope\RedEnvelopeProtocol;
use App\Repositories\RedEnvelope\RedEnvelopeRulesRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RedEnvelopeController extends Controller {

    /**
     * @var RedEnvelopeRulesRepository
     */
    private $ruleRepo;

    /**
     * RedEnvelopeController constructor.
     * @param RedEnvelopeRulesRepository $ruleRepo
     */
    public function __construct(RedEnvelopeRulesRepository $ruleRepo)
    {
        $this->ruleRepo = $ruleRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;

        $rules = $this->ruleRepo->getAllPaginated($status, $start_time, $end_time);

        return $this->response->paginator($rules, new RedEnvelopeRuleTransformer());
    }

    public function show($rule_id)
    {
        $rule = $this->ruleRepo->get($rule_id, true);

        return $this->response->item($rule, new RedEnvelopeRuleTransformer());
    }

    public function store(RedEnvelopeRuleRequest $request)
    {
        $rule = $this->ruleRepo->updateRule($request->all());

        return $this->response->item($rule, new RedEnvelopeRuleTransformer())->setStatusCode(201);
    }

    public function update(RedEnvelopeRuleRequest $request, $rule_id)
    {
        $rule = $this->ruleRepo->updateRule($request->all(), $rule_id);

        return $this->response->item($rule, new RedEnvelopeRuleTransformer());
    }

    public function active(Request $request, $rule_id)
    {
        $rule = $this->ruleRepo->setAsActive($rule_id);

        return $this->response->item($rule, new RedEnvelopeRuleTransformer());
    }

    public function unactive(Request $request, $rule_id)
    {
        $rule = $this->ruleRepo->setAsUnActive($rule_id);

        return $this->response->item($rule, new RedEnvelopeRuleTransformer());
    }


}
