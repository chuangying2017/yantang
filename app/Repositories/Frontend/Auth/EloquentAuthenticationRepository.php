<?php namespace App\Repositories\Frontend\Auth;

use App\Events\Auth\UserRegister;
use App\Models\Access\User\User;
use App\Exceptions\GeneralException;
use Illuminate\Contracts\Auth\Guard;
use App\Events\Auth\UserLoggedIn;
use App\Events\Auth\UserLoggedOut;
use App\Repositories\Frontend\User\UserContract;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class Registrar
 * @package App\Services
 */
class EloquentAuthenticationRepository implements AuthenticationContract {

    /**
     * @var Socialite
     */
    private $socialite;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var UserContract
     */
    private $users;

    /**
     * @param Socialite $socialite
     * @param Guard $auth
     * @param UserContract $users
     */
    public function __construct(Socialite $socialite, Guard $auth, UserContract $users)
    {
        $this->socialite = $socialite;
        $this->users = $users;
        $this->auth = $auth;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        $user = $this->users->create($data);

        return $user;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws GeneralException
     */
    public function login($request)
    {
        //@change to phone login
        if ($this->auth->attempt($request->only('phone', 'password'), $request->has('remember'))) {

            if ($this->auth->user()->status == 0) {
                $this->auth->logout();
                throw new GeneralException("Your account is currently deactivated.");
            }

            if ($this->auth->user()->status == 2) {
                $this->auth->logout();
                throw new GeneralException("Your account is currently banned.");
            }

            if ($this->auth->user()->confirmed == 0) {
                $user_id = $this->auth->user()->id;
                $this->auth->logout();
                throw new GeneralException("Your account is not confirmed. Please click the confirmation link in your e-mail, or " . '<a href="' . route('account.confirm.resend', $user_id) . '">click here</a>' . " to resend the confirmation e-mail.");
            }

            event(new UserLoggedIn($this->auth->user()));

            return true;
        }

        throw new GeneralException(\Lang::get('auth.failed'));
    }

    /**
     * Log the user out and fire an event
     */
    public function logout()
    {
        event(new UserLoggedOut($this->auth->user()));
        $this->auth->logout();
    }

    /**
     * Socialite Functions
     */

    /**
     * @param $request
     * @param $provider
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws GeneralException
     */
    public function loginThirdParty($request, $provider)
    {
        $user = $this->users->findByUserNameOrCreate($this->getSocialUser($provider), $provider);

        /**
         * The user can not log in with a social account if they have been banned or deactivated.
         */
        if ($user->isBannedOrDeactivated())
            throw new GeneralException("Your account has been banned or deactivated.");

        event(new UserLoggedIn($user));

        return $user;
    }

	/**
     * @param $request
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginThirdPartyUrl($request, $provider)
    {
        return $this->getAuthorizationFirst($provider);
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAuthorizationFirst($provider)
    {
        if ($provider == "weixin") {
            $scopes = [
                'snsapi_userinfo'
            ];
        }

        if (isset($scopes)) {
            return $this->socialite->driver($provider)->scopes($scopes)->redirect()->getTargetUrl();
        }

        return $this->socialite->driver($provider)->redirect()->getTargetUrl();
    }

    /**
     * @param $provider
     * @return \Laravel\Socialite\Contracts\User
     */
    public function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }

    /**
     * @param $token
     * @return mixed
     */
    public function confirmAccount($token)
    {
        return $this->users->confirmAccount($token);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function resendConfirmationEmail($user_id)
    {
        return $this->users->sendConfirmationEmail($user_id);
    }
}
