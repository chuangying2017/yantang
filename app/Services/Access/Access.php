<?php namespace App\Services\Access;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use App\Repositories\Store\StoreRepositoryContract;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * Class Access
 * @package App\Services\Access
 */
class Access {

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     * @param Request $request
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->request = $this->app->make(Request::class);
    }


    /**
     * @return User
     */
    public function user()
    {
        //从jwt token中获取用户
        if (!JWTAuth::setRequest($this->request)->getToken()) {

            if (auth('web')->check()) {
                return auth('web')->user();
            }

            return false;
        }

        try {
            $user = JWTAuth::setRequest($this->request)->parseToken()->authenticate();
            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     * Get the currently authenticated user's id
     */
    public function id()
    {
        if (!JWTAuth::setRequest($this->request)->getToken()) {
            if (auth('web')->check()) {
                return auth('web')->id();
            }

            return false;
        }

        try {
            $user = JWTAuth::setRequest($this->request)->parseToken()->authenticate();

            return $user->id;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function storeId()
    {
        return $this->store()->id;
    }

    public function store()
    {
        return $this->app->make(StoreRepositoryContract::class)->getStoreByUser($this->id());
    }

    public function staff()
    {
        return $this->app->make(StaffRepositoryContract::class)->getStaffByUser($this->id());
    }

    public function staffId()
    {
        return $this->staff()->id;
    }

    public function station()
    {
        return $this->app->make(StationRepositoryContract::class)->getStationByUser($this->id());
    }

    public function stationId()
    {
        return $this->station()->id;
    }

    public function getProviderId($provider = 'weixin')
    {
        $user = $this->user();
        return $user->providers()->where("provider", $provider)->pluck('provider_id')->first();
    }

    /**
     * Checks if the current user has a Role by its name or id
     *
     * @param string $role Role name.
     *
     * @return bool
     */
    public function hasRole($role)
    {
        $user = $this->user();
        if ($user)
            return $user->hasRole($role);

        return false;
    }

    public function addRole($role, $user_id = null)
    {
        if (!is_null($user_id) && $user_id) {
            try {
                $user = $this->app->make(EloquentUserRepository::class)->findOrThrowException($user_id);
            } catch (\Exception $e) {
                \Log::error($e);
            }
        } else {
            $user = $this->user();
        }

        if (is_object($role) || is_array($role)) {
            $role_id = $role['id'];
        } else if (is_numeric($role)) {
            $role_id = $role;
        } else {
            $role_id = Role::query()->where('name', $role)->pluck('id')->first();
        }

        if (!$user->hasRole($role_id)) {
            $user->attachRole($role_id);
        }
    }

    public function removeRole($role, $user_id = null)
    {
        if (!is_null($user_id) && $user_id) {
            try {
                $user = $this->app->make(EloquentUserRepository::class)->findOrThrowException($user_id);
            } catch (\Exception $e) {
                \Log::error($e);
            }
        } else {
            $user = $this->user();
        }

        if (is_object($role) || is_array($role)) {
            $role_id = $role['id'];
        } else if (is_numeric($role)) {
            $role_id = $role;
        } else {
            $role_id = Role::query()->where('name', $role)->pluck('id')->first();
        }

        if ($user->hasRole($role_id)) {
            $user->detachRole($role_id);
        }
    }

    /**
     * Checks if the user has either one or more, or all of an array of roles
     * @param $roles
     * @param bool $needsAll
     * @return bool
     */
    public function hasRoles($roles, $needsAll = false)
    {
        if ($user = $this->user()) {
            //If not an array, make a one item array
            if (!is_array($roles))
                $roles = array($roles);

            return $user->hasRoles($roles, $needsAll);
        }

        return false;
    }

    /**
     * Check if the current user has a permission by its name or id
     *
     * @param string $permission Permission name or id.
     *
     * @return bool
     */
    public function allow($permission)
    {
        if ($user = $this->user())
            return $user->allow($permission);

        return false;
    }

    /**
     * Check an array of permissions and whether or not all are required to continue
     * @param $permissions
     * @param $needsAll
     * @return bool
     */
    public function allowMultiple($permissions, $needsAll = false)
    {
        if ($user = $this->user()) {
            //If not an array, make a one item array
            if (!is_array($permissions))
                $permissions = array($permissions);

            return $user->allowMultiple($permissions, $needsAll);
        }

        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->allow($permission);
    }

    /**
     * @param $permissions
     * @param $needsAll
     * @return bool
     */
    public function hasPermissions($permissions, $needsAll = false)
    {
        return $this->allowMultiple($permissions, $needsAll);
    }


}
