<?php namespace App\Repositories\Client\UserGroup;

use App\Models\Client\UserGroupAbstract;
use App\Services\Client\ClientProtocol;

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
        $group = $this->getGroup($group_id);

        $group->update([
            'name' => $name,
            'priority' => $priority,
            'cover_image' => $cover_image,
        ]);

        return $group;
    }


    public function getAllGroups()
    {
        $model = $this->getModel();

        return $model::get();
    }

    public function getAllGroupsPaginated($per_page = ClientProtocol::GROUP_PER_PAGE)
    {
        $model = $this->getModel();

        return $model::paginate($per_page);
    }


    public function getGroup($id)
    {
        $model = $this->getModel();

        if ($id instanceof $model) {
            $group = $id;
        } else {
            $group = $model::findOrFail($id);
        }

        return $group;
    }

    public function getGroupByName($name)
    {
        $model = $this->getModel();

        if ($name instanceof $model) {
            $group = $name;
        } else {
            $group = $model::where('name', $name)->first();
        }

        return $group;
    }


    public function deleteGroup($group_id)
    {
        $this->groupRemoveAllUsers($group_id);

        $group = $this->getGroup($group_id);

        return $group->delete();
    }


    protected abstract function setModel();

    /**
     * @return UserGroupAbstract
     */
    protected function getModel()
    {
        return $this->model;
    }

    public function groupAddUsers($group_id, $user_ids)
    {
        $group = $this->getGroup($group_id);
        $change = $group->users()->sync($user_ids);
        $increase_count = count($change['attached']) - count($change['detached']);
        $group->user_count = $group->user_count + $increase_count;
        $group->save();

        return $group;
    }


    public function groupRemoveUsers($group_id, $user_ids)
    {
        $group = $this->getGroup($group_id);
        $detach_count = $group->users()->detach($user_ids);
        $group->user_count = $group->user_count - $detach_count;
        $group->save();

        return $group;
    }

    public function groupRemoveAllUsers($group_id)
    {
        $group = $this->getGroup($group_id);
        $group->users()->detach();
        $group->user_count = 0;
        $group->save();

        return $group;
    }

    public function userInGroup($user_id, $group_id)
    {
        return \DB::table('group_user')->where('user_id', $user_id)->where('group_id', $group_id)->count();
    }

    public function getGroupUsersPaginated($group_id, $per_page = ClientProtocol::GROUP_USERS_PER_PAGE)
    {
        $group = $this->getGroup($group_id);

        return $group->users()->paginate($per_page);
    }

    public function getGroupUsersAll($group_id)
    {
        $group = $this->getGroup($group_id);

        return $group->users()->get();
    }
}
