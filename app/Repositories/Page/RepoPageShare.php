<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/17/017
 * Time: 17:57
 */
namespace App\Repositories\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait RepoPageShare
{
    public function paginate(Builder $builder,$perPage = null,$page = null)
    {
            $page = $page ?: Paginator::resolveCurrentPage();

            $query  = $builder->toBase();

            $total = $query->getCountForPagination();

            $result = $total ? $builder->forPage($page, $perPage)->get() : new Collection();

            return new LengthAwarePaginator($result, $result->count()?:0,$perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);

    }

}