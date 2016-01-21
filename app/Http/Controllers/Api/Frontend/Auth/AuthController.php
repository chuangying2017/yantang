<?php namespace App\Http\Controllers\Api\Frontend\Auth;

use App\Services\Mth\MthApiService;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
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
//        $this->middleware('register.verify.phone', ['only' => 'postRegister']);
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

        return response()->json(compact('token'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return $this->response->array($this->getSocialLinks());
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        try {
            // attempt to verify the credentials and create a token for the user
            if ( ! $token = JWTAuth::attempt($request->only('phone', 'password'))) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * @param Request $request
     * @param $provider
     * @return mixed
     */
    public function loginThirdParty(Request $request, $provider)
    {
        return $this->auth->loginThirdParty($request->all(), $provider);
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

    /**
     * Generates social login links based on what is enabled
     * @return string
     */
    protected function getSocialLinks()
    {
        $socialite_enable = [];
        $socialite_links = '';

        if (getenv('GITHUB_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Github']), 'github');

        if (getenv('FACEBOOK_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Facebook']), 'facebook');

        if (getenv('TWITTER_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Twitter']), 'twitter');

        if (getenv('GOOGLE_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Google']), 'google');

        for ($i = 0; $i < count($socialite_enable); $i++) {
            $socialite_links .= ($socialite_links != '' ? '&nbsp;|&nbsp;' : '') . $socialite_enable[ $i ];
        }

        return $socialite_links;
    }
}
