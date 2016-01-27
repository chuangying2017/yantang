<?php namespace App\Http\Controllers\Api\Frontend\Auth;

use App\Http\Requests\ThirdPartyRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Transformers\UserInfoTransformer;
use App\Services\Auth\AccountService;
use App\Services\Mth\MthApiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Frontend\Access\LoginRequest;
use App\Http\Requests\Frontend\Access\RegisterRequest;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend\Auth
 */
class AuthController extends Controller {

    use ThrottlesLogins;

    /**
     * @param AuthenticationContract $auth
     */
    public function __construct(AuthenticationContract $auth)
    {
        $this->auth = $auth;
        $this->middleware('register.verify.phone', ['only' => ['postRegister', 'updatePhone']]);
    }


    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(RegisterRequest $request)
    {

        $phone = $request->input('phone');
        $password = $request->input('password');
        $info = MthApiService::registerGetUser($phone, $password);

        if ( ! $info) {
            $this->response->errorInternal('每天惠api错误');
        }

        $user = $this->auth->create($request->all());

        $token = JWTAuth::fromUser($user);
        $roles = UserInfoTransformer::getRoles($user);

        return response()->json(compact('token', 'roles'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        if (\Auth::check()) {
            return redirect()->route('backend.dashboard');
        }

        return view('frontend.auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request))
            return $this->sendLockoutResponse($request);


        try {
            // attempt to verify the credentials and create a token for the user
            if ( ! $token = JWTAuth::attempt($request->only('phone', 'password'))) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = JWTAuth::toUser($token);
            $roles = UserInfoTransformer::getRoles($user);

            // all good so return the token
            return response()->json(['data' => compact('token', 'roles')]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
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

            $roles = UserInfoTransformer::getRoles($user);

            return $this->response->array(['data' => compact('token', 'roles')]);
        } catch (\Exception $e) {
            $this->response->errorInternal('无效请求');
        }
    }

    public function loginThirdPartyUrl(Request $request, $provider)
    {
        $url = $this->auth->loginThirdPartyUrl($request->all(), $provider);

        return $this->response->array(['data' => compact('url')]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect()->route('home');
    }


    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }


    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }

    protected function loginUsername()
    {
        return 'phone';
    }


    public function updatePhone(UpdatePhoneRequest $request)
    {
        try {
            $user = $this->getCurrentAuthUser();
            $phone = $request->input('phone');

            $user = AccountService::updatePhone($user, $phone);

            return $this->response->item($user, new UserInfoTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }

}
