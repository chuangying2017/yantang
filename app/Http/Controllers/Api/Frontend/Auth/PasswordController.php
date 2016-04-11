<?php namespace App\Http\Controllers\Api\Frontend\Auth;

use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\Frontend\User\EloquentUserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\PasswordBroker;
use App\Repositories\Frontend\User\UserContract;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\Frontend\Access\ChangePasswordRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller {


    /**
     * @param Guard $auth
     * @param PasswordBroker $passwords
     * @param UserContract $user
     */
    public function __construct(Guard $auth, UserContract $user)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->middleware('register.verify.phone', ['only' => ['postReset']]);
    }

    public function postReset(ResetPasswordRequest $request)
    {
        $phone = $request->input('phone');

        EloquentUserRepository::resetPassword($phone, $request->input('password'));

        return $this->response->array(['message' => 'success']);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return mixed
     */
    public function postChangePassword(ChangePasswordRequest $request)
    {
        try {
            $this->user->changePassword($request->all());

            return $this->response->array(['message' => '成功']);
        } catch (\Exception $e) {
            $this->response->errorForbidden($e->getMessage());
        }
    }
}
