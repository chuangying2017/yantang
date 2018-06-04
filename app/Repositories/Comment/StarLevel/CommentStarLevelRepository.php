<?php
/**
 * Created by PhpStorm.
 * User: 张伟
 * Date: 2018/6/5
 * Time: 0:59
 */

namespace App\Repositories\Comment\StarLevel;


use App\Models\Comments\LabelSet;
use App\Models\Settings;

class CommentStarLevelRepository implements CommentStarLevelRepositoryContract
{

    /**
     * @param $data
     */
    public function store($data)
    {
        // TODO: Implement store() method.
        LabelSet::updateOrCreate();
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return [
            'label' => LabelSet::all(['id','title','star_level']),
            'settings' => Settings::find(1)
        ];
    }

    public function getAllPaginated()
    {
        // TODO: Implement getAllPaginated() method.
    }
}