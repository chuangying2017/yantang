<?php namespace App\Repositories\Subscribe\Preorder;


interface PreorderRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param int $user_id
     * @return mixed
     */
    public function byUserId($user_id);

    public function update($input, $preorder_id);
}