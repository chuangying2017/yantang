<?php

namespace App\Api\V1\Controllers\Gateway;

use App\Repositories\Image\QiniuImageRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QiniuNotifyController extends Controller {


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, QiniuImageRepository $imageRepo)
    {
        $data = $request->all();
        $media_id = $request->input('media_id') . '-' . date('YmdHis') . mt_rand(1, 9999);
        $data['media_id'] = $media_id;
        $imageRepo->create($data);

        return response()->json([
            'key' => $media_id,
            'payload' => [
                'success' => true,
                'name' => $media_id
            ]
        ]);
    }


}
