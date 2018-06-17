<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/17/017
 * Time: 17:57
 */
namespace App\Repositories\Page;
use Illuminate\Database\Eloquent\Builder;

trait RepoPageShare
{
    public function paginate(Builder $builder,$perPage = null,$columns = ['*'], $pageName = 'page', $page = null){

    }
}