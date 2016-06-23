<?php namespace App\Repositories\Client\UserGroup;

interface UserGroupAttachContract {

    public function GroupAddUsers($user_ids, $group_id);

    public function addUserToGroups($user_id, $group_ids);

    public function removeUserFromGroup($user_id, $group_id);

    public function removeUserAllGroup($user_id);

    public function GroupRemoveAllUsers($group_id);

    public function userInGroups($user_id, $group_ids);

}
