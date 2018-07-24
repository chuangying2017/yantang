<?php
namespace App\Repositories\Common\Decorate;

abstract class CommonDecorate
{
    protected abstract function handle($data,$model);

    protected $WhitEditor;

    public function editWith($model)
    {
        $this->WhitEditor = $model;
    }

    public function next($data,$model)
    {
        if ($this->WhitEditor)
        {
            $this->WhitEditor->handle($data,$model);
        }

        return compact('data','model');
    }
}