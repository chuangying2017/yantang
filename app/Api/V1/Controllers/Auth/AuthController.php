<?php namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Transformers\Auth\UserLoginTransformer;
use App\Exceptions\GeneralException;
use App\Api\V1\Controllers\Controller;
use App\Models\Monitors;
use Dingo\Api\Facade\Route;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Repositories\Auth\AuthenticationContract;
use App\Api\V1\Requests\Auth\LoginRequest;
use App\Api\V1\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Api\V1\Requests\Auth\ThirdPartyRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

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
     * @return \Dingo\Api\Http\Response
     */
    public function postRegister(RegisterRequest $request)
    {
        try {
            $user = $this->auth->create($request->all());

            return $this->response->item($user, new UserLoginTransformer());
        } catch (JWTException $e) {
            $this->response->error('could_not_create_token', 500);
        }
    }

    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function postLogin(Request $request)
    {
        try {
            if (!$token = JWTAuth::attempt($request->only('phone', 'password'))) {
                $this->response->errorUnauthorized();
            }

            return $this->response->item(JWTAuth::toUser($token), new UserLoginTransformer());
        } catch (JWTException $e) {
            $this->response->error('could_not_create_token', 500);
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

            Monitors::create(['action'=>Route::current()->uri()]);

            $user = $this->auth->loginThirdParty($request->all(), $provider);

            return $this->response->item($user, new UserLoginTransformer());
        } catch (\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function loginThirdPartyUrl(Request $request, $provider)
    {
        $url = $this->auth->loginThirdPartyUrl($request->all(), $provider);
        if( $request->input('role') == 'deliver' ){
            //get parameter
            $redirectArr = parse_url( $url );
            //resolve
            $paramsArr = [];
            parse_str( $redirectArr['query'], $paramsArr );
            //decode url
            $redirect_uri = urldecode( $paramsArr['redirect_uri'] );


            //get parameter of redirect uri
            $reParam = parse_url( $redirect_uri );
            $reParamArr = [];
            parse_str( $reParam['query'], $reParamArr );
            //add back param
            $reParamArr['back'] = $request->input('backURL');
            $reParam['query'] = http_build_query( $reParamArr );
            $redirect_uri = http_build_url( $reParam );

            //add to old return url
            $paramsArr['redirect_uri'] = $redirect_uri;
            //build query
            $redirectArr['query'] = http_build_query( $paramsArr );

            //build url
            $url = http_build_url( $redirectArr );
            //return it
        }

        return $this->response->array(['data' => compact('url')]);
    }


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
