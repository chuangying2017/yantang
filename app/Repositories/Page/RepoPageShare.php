<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/17/017
 * Time: 17:57
 */
namespace App\Repositories\Page;
use Illuminate\Database\Eloquent\Collection;

trait RepoPageShare
{
    public function paginate(Collection $collection,$perPage = null,$page = null)
    {
        return $collection->forPage($page,$perPage)->all();
    }

}