<?php namespace App\Repositories\Auth\User;

use App\Events\Auth\UserRegister;
use App\Models\Access\User\User;
use App\Models\Access\User\UserProvider;
use App\Exceptions\GeneralException;
use App\Repositories\Auth\User\UserContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\User
 */
class EloquentUserRepository implements UserContract, PromotionAbleUserContract {

    /**
     * @var RoleRepositoryContract
     */
    protected $role;

    /**
     * @param RoleRepositoryContract $role
     */
    public function __construct(RoleRepositoryContract $role)
    {
        $this->role = $role;
    }

    /**
     * @param $id
     * @return User
     * @throws GeneralException
     */
    public function findOrThrowException($id)
    {
        $user = User::find($id);
        if (!is_null($user)) return $user;

        throw new GeneralException('That user does not exist.');
    }

    /**
     * @param $data
     * @param bool $provider
     * @return static
     */
    public function create($data, $provider = false)
    {
        $user = User::create([
            'email' => array_get($data, 'email', ''),
            'phone' => array_get($data, 'phone', null),
            'password' => $provider ? null : $data['password'],
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'status' => 1,
            'confirmed' => config('access.users.confirm_email') ? 0 : 1,
        ]);

        $user->attachRole($this->role->getDefaultUserRole());

//        $user->confirmed = $user['phone'] ? 1 : 0;
        $user->confirmed = 1;

        event(new UserRegister($user, $data));

        return $user;
    }

    /**
     * @param $data
     * @param $provider
     * @return static
     */
    public function findByUserNameOrCreate($data, $provider)
    {
        $user = access()->user();;

        $user_provider = UserProvider::with('user')->where('provider_id', $data->id)->where('provider', $provider)->first();

        //用户未登录
        if (!$user) {
            /**
             * 通过provider查找用户是否存在
             * 用户更新电话号码后选择绑定到已有用户,先查询是否存在使用该手机的用户,没有则绑定激活临时用户,有则绑定已注册用户
             */

            // 不存在第三方授权信息且为当前为微信开放平台或微信授权,使用union id 查询是否存在绑定用户
            if (!$user_provider && ($provider == 'weixin' || $provider == 'weixinweb') && !is_null($data->union_id) && $data->union_id) {
                $user_provider = UserProvider::with('user')->where('union_id', $data->union_id)->first();
            }

            /*
            * 不存在授权信息,创建用户
            */
            if (!$user_provider) {
                $user_data = [
                    'nickname' => property_exists($data, 'nickname') ? $data->nickname : uniqid($provider . '_'),
                    'email' => $data->email ?: '',
                    'avatar' => $data->avatar,
                    'phone' => property_exists($data, 'phone') ? $data->phone : null,
                    'sex' => property_exists($data, 'sex') ? $data->sex : '',
                    'status' => 1
                ];
                $user = $this->create($user_data, true);
            } else {
                $user = $user_provider->user;
            }
        } else {
            // 用户已登录,且授权信息已存在,若两者不是相互绑定则抛出异常
            if ($user_provider) {
                if ($user['id'] != $user_provider['user_id']) {
                    throw new UnauthorizedHttpException('授权无效,已被其他帐号绑定');
                }
            }
        }

        $providerData = [
            'avatar' => $data->avatar,
            'nickname' => (property_exists($data, 'nickname') ? $data->nickname : $data->name),
            'provider' => $provider,
            'provider_id' => $data->id,
            'union_id' => (property_exists($data, 'union_id') ? $data->union_id : null)
        ];


        if ($this->hasProvider($user, $provider))
            //用户已绑定,检查授权信息是否需要更新
            $this->checkIfUserNeedsUpdating($provider, $data, $user);
        else {
            //用户未绑定第三方授权,绑定
            $user->providers()->save(new UserProvider($providerData));
        }

        return $user;
    }


    /**
     * @param $user
     * @param $provider
     * @return bool
     */
    public function hasProvider($user, $provider)
    {
        foreach ($user->providers as $p) {
            if ($p->provider == $provider)
                return true;
        }

        return false;
    }

    /**
     * @param $provider
     * @param $providerData
     * @param $user
     * @return mixed|void
     */
    public function checkIfUserNeedsUpdating($provider, $providerData, $user)
    {
        //Have to first check to see if name and email have to be updated
        $userData = [
            'email' => $providerData->email,
            'name' => $providerData->name,
        ];
        $dbData = [
            'email' => $user->email,
            'name' => $user->name,
        ];
        $differences = array_diff($userData, $dbData);
        if (!empty($differences)) {
            $user->email = $providerData->email;
            $user->name = $providerData->name;
            $user->save();
        }

        //Then have to check to see if avatar for specific provider has changed
        $p = $user->providers()->where('provider', $provider)->first();
        if ($p->avatar != $providerData->avatar) {
            $p->avatar = $providerData->avatar;
            $p->save();
        }
    }

    /**
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function updateProfile($input)
    {
        $user = access()->user();
        $user->name = $input['name'];

        if ($user->canChangeEmail()) {
            //Address is not current address
            if ($user->email != $input['email']) {
                //Emails have to be unique
                if (User::where('email', $input['email'])->first())
                    throw new GeneralException("That e-mail address is already taken.");

                $user->email = $input['email'];
            }
        }

        return $user->save();
    }

    /**
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function changePassword($input)
    {
        $user = $this->findOrThrowException(auth()->id());

        if (Hash::check($input['old_password'], $user->password)) {
            //Passwords are hashed on the model
            $user->password = $input['password'];

            return $user->save();
        }

        throw new GeneralException("That is not your old password.");
    }

    /**
     * @param $token
     * @throws GeneralException
     */
    public function confirmAccount($token)
    {
        $user = User::where('confirmation_code', $token)->first();

        if ($user) {
            if ($user->confirmed == 1)
                throw new GeneralException("Your account is already confirmed.");

            if ($user->confirmation_code == $token) {
                $user->confirmed = 1;

                return $user->save();
            }

            throw new GeneralException("Your confirmation code does not match.");
        }

        throw new GeneralException("That confirmation code does not exist.");
    }

    /**
     * @param $user
     * @return mixed
     */
    public function sendConfirmationEmail($user)
    {
        //$user can be user instance or id
        if (!$user instanceof User)
            $user = User::findOrFail($user);

        return Mail::queue('emails.confirm', ['token' => $user->confirmation_code], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject(app_name() . ': Confirm your account!');
        });
    }

    public static function findUserByPhone($phone)
    {
        return User::where('phone', $phone)->first();
    }

    public function findUserIdByProviderId($provider_id, $provider='weixin'){
        return UserProvider::query()
                ->where('provider_id', $provider_id)
                ->pluck('user_id')
                ->first();
    }

    public static function updatePhone($user_id, $phone)
    {
        $user = User::findOrFail($user_id);
        $user->phone = $phone;
        $user->confirmed = 1;
        $user->save();

        return $user;
    }

    public static function resetPassword($phone, $password)
    {
        $user = self::findUserByPhone($phone);
        if ($user) {
            $user->password = $password;

            return $user->save();
        }

        throw new ModelNotFoundException('用户不存在');
    }


    public function getUserInfo($user_id, $with_client = false, $with_roles = true)
    {
        $relation = [];
        if ($with_client) {
            $relation[] = 'client';
        }
        if ($with_roles) {
            $relation[] = 'roles';
        }

        if ($user_id instanceof User) {
            $user = $user_id->load($relation);
            return $user;
        }

        if (count($relation)) {
            return User::with($relation)->find($user_id);
        }
        return User::query()->find($user_id);
    }

    //优惠信息
    public function setUser($user)
    {
        if ($user instanceof User) {
            $this->user = $user;
        } else {
            $this->user = $this->findOrThrowException($user);
        }
        return $this;
    }

    public function getUserId()
    {
        return $this->user->id;
    }

    public function getUserLevel()
    {

    }

    public function getUserRoles()
    {
        $this->user->load('roles');
        return $this->user->roles;
    }

    protected $user;

    public function getGroup()
    {
        return \DB::table('group_user')->where('user_id', $this->user->id)->pluck('group_id');
    }

    //关注信息
    public function getProviderId($user_id, $provider = 'weixin')
    {
        return UserProvider::query()->where('user_id', $user_id)->where("provider", $provider)->pluck('provider_id')->first();
    }

    public function subscribeWeixin($user_id)
    {
        $openid = $this->getProviderId($user_id);

        try {
            $weixin_user_info =  \EasyWeChat::user()->get($openid);
            return $weixin_user_info['subscribe'];
        } catch (\Exception $e) {
            return false;
        }
    }
}
