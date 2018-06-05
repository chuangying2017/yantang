<?php
/**
 * Created by PhpStorm.
 * User: 张伟
 * Date: 2018/6/5
 * Time: 0:59
 */

namespace App\Repositories\Comment\StarLevel;


use App\Models\Settings;

class CommentStarLevelRepository implements CommentStarLevelRepositoryContract
{

    /**
     * @param $data
     */
    public function store($data)
    {
        // TODO: Implement store() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function update($data,$id)
    {
        // TODO: Implement update() method.
       $find = Settings::find($id);
       $find->fill($data);
       return $find->save();
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getAllPaginated()
    {
        // TODO: Implement getAllPaginated() method.
    }
}