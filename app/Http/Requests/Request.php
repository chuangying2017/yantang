<?php namespace App\Http\Requests;

use App\Http\Traits\ApiHelpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class Request
 * @package App\Http\Requests
 */
abstract class Request extends FormRequest {

    use ApiHelpers;

    public function forbiddenResponse()
    {
        return $this->respondForbidden();
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $errors = array_map(function ($error) {
            return implode(',', $error);
        }, $errors);

//        if ($this->ajax() || $this->wantsJson()) {
        return $this->respondUnProcessableEntity($errors);
//        }

//        return $this->redirector->to($this->getRedirectUrl())
//            ->withInput($this->except($this->dontFlash))
//            ->withErrors($errors, $this->errorBag);
    }

}

