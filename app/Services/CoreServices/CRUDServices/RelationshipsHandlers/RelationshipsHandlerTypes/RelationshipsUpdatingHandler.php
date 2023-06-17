<?php

namespace App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandlerTypes;

use App\Models\WorkSector\ClientsModule\ClientAttachment;
use App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandler;
use Exception;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

class RelationshipsUpdatingHandler extends RelationshipsHandler
{

    /**
     * @param Model $model
     * @param array $data
     * @return bool
     * @throws Exception
     */
    protected function updateSingleModel(Model $model ,  array $data ) : bool
    {
        $model = $this->ModelFilesHandling($model , $data);
        if(!$model->save()){return false;}
        $this->HandleModelOwnedRelationships( $data ,  $model);
        return true;
    }

    protected function getDataCollection(Model | EloquentCollection $modelOrCollection) : SupportCollection | EloquentCollection
    {
        return $modelOrCollection instanceof EloquentCollection ? $modelOrCollection :  collect($modelOrCollection);
    }

    protected function getConvenientModelForUpdating(SupportCollection | EloquentCollection $modelOrCollection  ,array $data , string $primaryKeyName) : Model
    {
        return $modelOrCollection->filter(function($item) use($data , $primaryKeyName){
            if($item->{$primaryKeyName} == $data[$primaryKeyName]) { return $item; }
        })->first();
    }

    /**
     * @param Model $model
     * @param string $relationship
     * @param array $relationshipMultipleRows
     * @param string $primaryKeyName
     * @return bool
     * @throws Exception
     */
    protected function OwnedRelationshipRowsChildClassHandling(Model $model, string $relationship, array $relationshipMultipleRows , string $primaryKeyName = "") : bool
    {
        $modelOrCollection = $model->{$relationship};
        if(!$modelOrCollection){return false;}

        $modelOrCollection = $this->getDataCollection($modelOrCollection);
        foreach ($relationshipMultipleRows as $row)
        {
            if( !array_key_exists($primaryKeyName , $row)){return false;}
            $modelToUpdate = $this->getConvenientModelForUpdating($modelOrCollection , $row , $primaryKeyName);
            $this->updateSingleModel(  $modelToUpdate ,  $row);
        }

        return true;
    }

    protected function ParticipatingRelationshipRowsChildClassHandling(Model $model, string $relationshipName, array $ParticipatingRelationshipMultipleRows): bool
    {
        $model->{$relationshipName}()->sync( $ParticipatingRelationshipMultipleRows );
        return true;
    }
}
