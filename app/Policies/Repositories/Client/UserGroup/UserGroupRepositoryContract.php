<?php namespace App\Repositories\Client\UserGroup;
interface UserGroupRepositoryContract {

    public function createGroup($name, $priority = 0, $cover_image = null);

    public function updateGroup($group_id, $name, $priority = 0, $cover_image = null);

    public function getAllGroups();

    public function deleteGroup($group_id);

    public function getGroup($id);

    public function getGroupByName($name);

}
