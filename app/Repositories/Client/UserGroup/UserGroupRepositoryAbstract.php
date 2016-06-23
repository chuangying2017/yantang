<?php namespace App\Repositories\Client\UserGroup;

use App\Models\Client\UserGroupAbstract;

abstract class UserGroupRepositoryAbstract implements UserGroupRepositoryContract, UserGroupAttachContract {

    /**
     * @var UserGroupAbstract
     */
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    public function createGroup($name, $priority = 0, $cover_image = null)
    {
        $model = $this->getModel();

        $group = $this->getGroupByName($name, false);

        if ($group) {
            throw new \Exception('用户分组已存在');
        }

        $group = $model::create([
            'name' => $name,
            'priority' => $priority,
            'cover_image' => $cover_image,
        ]);

        return $group;
    }

    public function updateGroup($group_id, $name, $priority = 0, $cover_image = null)
    {
        $group = $this->getGroupByName($name, false);

        if ($group && $group['id'] !== $group_id) {
            throw new \Exception('用户分组已存在');
        }

        $group->update([
            'name' => $name,
            'priority' => $priority,
            'cover_image' => $cover_image,
        ]);

        return $group;
    }

    public function getAll()
    {
        $model = $this->getModel();

        return $model::get();
    }

    public function getGroup($id, $with_user = true)
    {
        $model = $this->getModel();

        if ($id instanceof $model) {
            $group = $id;
        } else {
            $group = $model::findOrFail($id);
        }


        if ($with_user) {
            $group->load('users');
        }

        return $group;
    }

    public function getGroupByName($name, $with_user = true)
    {
        $model = $this->getModel();

        if ($name instanceof $model) {
            $group = $name;
        } else {
            $group = $model::where('name', $name)->first();
        }

        if ($with_user) {
            $group->load('users');
        }

        return $group;
    }

    protected abstract function setModel();

    /**
     * @return UserGroupAbstract
     */
    protected function getModel()
    {
        return $this->model;
    }

    public function GroupAddUsers($user_ids, $group_id)
    {
        // TODO: Implement GroupAddUsers() method.
    }

    public function addUserToGroups($user_id, $group_ids)
    {
        // TODO: Implement addUserToGroups() method.
    }

    public function removeUserFromGroup($user_id, $group_id)
    {
        // TODO: Implement removeUserFromGroup() method.
    }

    public function removeUserAllGroup($user_id)
    {
        // TODO: Implement removeUserAllGroup() method.
    }

    public function GroupRemoveAllUsers($group_id)
    {
        // TODO: Implement GroupRemoveAllUsers() method.
    }

    public function userInGroups($user_id, $group_ids)
    {
        // TODO: Implement userInGroups() method.
    }
}
