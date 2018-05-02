<?php namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\BannerTransformer;
use App\Services\Home\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller {
    public function index(Request $request)
    {
        $type = $request->input('type');
        $banners = BannerService::listByType( $type );

        return $this->response->array($banners, new BannerTransformer());
    }
}
