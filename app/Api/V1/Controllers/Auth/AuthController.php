<?php namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Transformers\Auth\UserLoginTransformer;
use App\Exceptions\GeneralException;
use App\Api\V1\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use App\Api\V1\Requests\Auth\LoginRequest;
use App\Api\V1\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use JWTAuth;
use App\Api\V1\Transformers\Auth\UserInfoTransformer;
use App\Api\V1\Requests\Auth\ThirdPartyRequest;


/**
 * Class AuthController
 * @package App\Api\V1\Controllers\Frontend\Auth
 */
class AuthController extends Controller {

    use ThrottlesLogins;

    /**
     * @param AuthenticationContract $auth
     */
    public function __construct(AuthenticationContract $auth)
    {
        $this->auth = $auth;
        $this->middleware('auth.phone.sms.verify', ['only' => ['postRegister']]);
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Api\V1\RedirectResponse
     */
    public function postRegister(RegisterRequest $request)
    {
        try {
            $user = $this->auth->create($request->all());
            $token = JWTAuth::fromUser($user);

            return $this->response->item($token, new UserLoginTransformer());
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Api\V1\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        try {
            if (!$token = JWTAuth::attempt($request->only('phone', 'password'))) {
                return $this->response->errorUnauthorized();
            }

            return $this->response->item($token, new UserLoginTransformer());
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }
    }

    /**
     * @param Request $request
     * @param $provider
     * @return mixed
     */
    public function loginThirdParty(ThirdPartyRequest $request, $provider)
    {
        try {
            $user = $this->auth->loginThirdParty($request->all(), $provider);
            $token = JWTAuth::fromUser($user);

            return $this->response->item($token, new UserLoginTransformer());
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function loginThirdPartyUrl(Request $request, $provider)
    {
        $url = $this->auth->loginThirdPartyUrl($request->all(), $provider);

        return $this->response->array(['data' => compact('url')]);
    }


    /**
     * @return \Illuminate\Api\V1\RedirectResponse
     */
    public function getLogout()
    {
        $this->auth->logout();

        return $this->response->noContent();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'phone';
    }

    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }


}
