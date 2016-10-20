<?php

namespace App\Api\V1\Controllers\Admin\Image;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\ImageTransformer;
use App\Repositories\Image\ImageRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{
    /**
     * @var ImageRepositoryContract
     */
    private $imageRepo;

    /**
     * ImageController constructor.
     * @param ImageRepositoryContract $imageRepo
     */
    public function __construct(ImageRepositoryContract $imageRepo)
    {
        $this->imageRepo = $imageRepo;
    }

    public function index()
    {
        $images = $this->imageRepo->getAllPaginated();

        return $this->response->paginator($images, new ImageTransformer());
    }

    public function token()
    {
        $token = $this->imageRepo->getToken();

        return $this->response->array(['data' => ['token' => $token]]);
    }
}
