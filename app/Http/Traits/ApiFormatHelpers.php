<?php namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ApiFormatHelpers {

    public static function transform(Collection $collections, array $rules)
    {
        if ($collections->count() > 1) {
            foreach ($collections as $key => $collection) {
                $collections->$key = array_only($collection, $rules);
            }
        }

        return $collections;
    }

}
