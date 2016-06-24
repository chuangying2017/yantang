<?php namespace App\Repositories\Client\UserGroup;

interface UserGroupAttachContract {

    public function groupAddUsers($user_ids, $group_id);

    public function groupRemoveUsers($group_id, $user_ids);

    public function groupRemoveAllUsers($group_id);

    public function userInGroup($user_id, $group_ids);

    public function getGroupUsersPaginated($group_id, $per_page);

    public function getGroupUsersAll($group_id);
}
