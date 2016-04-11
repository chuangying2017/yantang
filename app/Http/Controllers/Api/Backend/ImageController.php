<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Transformers\ImageTransformer;
use App\Services\Image\ImageService;
use App\Services\Merchant\MerchantService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageController extends Controller {

    protected $merchant_id;

    public function __construct()
    {
        $user_id = $this->getCurrentAuthUserId();
        $this->merchant_id = MerchantService::getMerchantIdByUserId($user_id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $images = ImageService::getByMerchant($this->merchant_id);

        return $this->response->paginator($images, new ImageTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function token()
    {
        $token = ImageService::getToken($this->merchant_id);

        return $this->response->array(['data' => $token]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $images_id = $request->input('images_id');

        ImageService::delete($images_id, $this->merchant_id);

        return $this->response->noContent();
    }
}
