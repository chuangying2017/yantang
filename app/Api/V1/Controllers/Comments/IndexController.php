<?php

namespace App\Api\V1\Controllers\Comments;

use App\Repositories\setting\SetMode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    //
    protected $set_mode;

    public function __construct(SetMode $setMode)
    {
        $this->set_mode = $setMode;
    }

    public function show($id)//show setting star level content
    {

        try {
            return $this->response->array($this->set_mode->getSetting($id));
        } catch (\ErrorException $e) {
            Log::error($e->getMessage());
        }

    }
}
