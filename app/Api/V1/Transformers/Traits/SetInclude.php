<?php namespace App\Api\V1\Transformers\Traits;

use Illuminate\Database\Eloquent\Model;

trait SetInclude {

    protected function setInclude(Model $model)
    {
        foreach ($this->getAvailableIncludes() as $include) {
            if ($model->relationLoaded($include)) {
                array_push($this->defaultIncludes, $include);
            }
        }
    }

}
