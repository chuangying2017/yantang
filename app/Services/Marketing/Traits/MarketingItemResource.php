<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 11/18/15
 * Time: 9:38 PM
 */

namespace App\Services\Marketing\Traits;


trait MarketingItemResource {




    protected $resource_type;

    /**
     * @return mixed
     */
    public function getResourceType()
    {
        return $this->resource_type;
    }

    /**
     * @param mixed $resource_type
     */
    public function setResourceType($resource_type)
    {
        $this->resource_type = $resource_type;
        return $this;
    }

}
