<?php namespace App\Api\V1\Controllers\Admin\Station;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;
use StatementsService;

class AdminStatementsController extends Controller
{
    public function __construct()
    {

    }

    public function createBilling(Request $request)
    {
        $input = $request->only(['begin_time', 'end_time']);
        StatementsService::create($input);
    }
}