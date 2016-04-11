<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Product\Tags\ProductTagService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends Controller {


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('data.name');

        $data = ProductTagService::find($name);

        return $this->response->array(['data' => $data]);
    }

}
