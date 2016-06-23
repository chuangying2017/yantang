<?php namespace App\Repositories\Client\UserGroup;
interface UserGroupRepositoryContract {

    public function createGroup($name, $priority = 0, $cover_image = null);

    public function updateGroup($group_id, $name, $priority = 0, $cover_image = null);

    public function getAll();

    public function getGroup($id, $with_user = true);

    public function getGroupByName($name, $with_user = true);

}
