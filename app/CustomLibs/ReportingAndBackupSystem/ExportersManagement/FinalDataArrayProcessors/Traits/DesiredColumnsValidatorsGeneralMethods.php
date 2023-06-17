<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait DesiredColumnsValidatorsGeneralMethods
{

    protected function showModelAllHiddenAttributes(Model $model) : Model
    {
        return $model->makeVisible($model->getHidden());
    }

    protected function getModelAllKeysArray(  Model $model) : array | null
    {
        return array_keys(
            $this->showModelAllHiddenAttributes($model)->toArray()
        );
    }

    protected function getModelRelationshipsAttributesKeysArray(Model $model) : array
    {
        return array_keys(
            $this->showModelAllHiddenAttributes($model)->relationsToArray()
        );
    }

    protected function getModelAttributesKeysArray(Model $model) : array
    {
        return array_keys(
            $this->showModelAllHiddenAttributes($model)->attributesToArray()
        );
    }

    protected function getCollectionModel(Collection | Model | null $modelOrCollection = null) :  Model | null
    {
        if(!$modelOrCollection){return null;}
        if($modelOrCollection instanceof Collection) {$modelOrCollection = $modelOrCollection->first();}
        return $modelOrCollection;
    }

    protected function getModelOrCollectionAllAttributesKeysArray(Collection | Model | null $modelOrCollection = null) : array | null
    {
        return $this->getModelAllKeysArray(
            $this->getCollectionModel($modelOrCollection)
        );
    }

    protected function getModelOrCollectionAttributesKeysArray(Collection | Model | null $modelOrCollection = null) : array | null
    {
        return $this->getModelAttributesKeysArray(
                    $this->getCollectionModel($modelOrCollection)
                );
    }
}
