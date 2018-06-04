<?php

namespace App\Api\V1\Controllers\Admin\Comments;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    //
    public function Index(){}

    public function show($comments_id){}

    public function update(Request $request, $comments_id){}

    public function store(Request $request){}

    public function edit($comments_id){}
}
