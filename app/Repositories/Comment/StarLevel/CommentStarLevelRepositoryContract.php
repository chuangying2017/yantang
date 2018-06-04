<?php
/**
 * Created by PhpStorm.
 * User: 张伟
 * Date: 2018/6/5
 * Time: 0:47
 */

namespace App\Repositories\Comment\StarLevel;


interface CommentStarLevelRepositoryContract
{

    public function store($data);

    public function create();

    public function update();

    public function getAll();

    public function getAllPaginated();
}