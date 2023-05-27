<?php

namespace App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait DesiredColumnsValidatorsGeneralMethods
{
    protected function getAllModelAttributesKeysArray(Collection | Model | null $modelOrCollection = null) : array | null
    {
        if(!$modelOrCollection){return null;}
        if($modelOrCollection instanceof Collection) {$modelOrCollection = $modelOrCollection->first();}
        return array_keys(
            $modelOrCollection->makeVisible($modelOrCollection->getHidden())->attributesToArray()
        );
    }
}
