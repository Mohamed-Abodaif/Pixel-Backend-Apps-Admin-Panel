<?php

namespace App\CustomLibs\ExportersManagement\Exporter\Traits;

use App\CustomLibs\ExportersManagement\Exporter\Exporter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait FinalDataArrayDesiredColumnsValidators
{

    protected array $ModelDesiredFinalColumns = [];
    protected array $RelationshipsDesiredFinalColumns = [];

    /**
     * @return array
     * This method is useful to determine the desired columns of model
     * Note  : that if the result is an empty array ... That means we want all retrieved columns of the model (not all actual columns ... ONLY Retrieved Columns)
     */
    abstract protected function getModelDesiredFinalColumns() : array  ;

    /**
     * @return FinalDataArrayHandlerTrait|Exporter
     */
    protected function setModelDesiredFinalDefaultColumns() : self
    {
        if(!empty($this->getModelDesiredFinalColumns()))
        {
            $this->ModelDesiredFinalColumns = $this->getModelDesiredFinalColumns();
            return $this;
        }

        //There Is No Need To Check If DataCollection Has Data Or Not
        //Because That Always Has Data Items If Execution Arrived at This Point
        /** @var Model $model */
        $model = $this->DataCollection->first();
        $this->ModelDesiredFinalColumns = $this->getAllModelAttributesKeysArray($model);
        return  $this;
    }

    /**
     * @return array
     *
     * Result Array will be like [ Relationship => Details Array ]
     *
     * Don't Forget To Include The Relationship in with relationships array
    otherwise the relationship will be loaded dynamically by single query for each model's relationship

     * Details Array will be like
    [
    "columns" => [columns and sub relationships array] ,
    "columns_prefix" => "column prefix resetting value , if it is not set the relation name will be used as prefix"
    ]
     */
    abstract protected function getRelationshipsDesiredFinalColumns() : array ;


    /**
     * @return FinalDataArrayHandlerTrait|Exporter
     */
    protected function setRelationshipsDefaultDesiredFinalColumns() : self
    {
        $RelationshipsDesiredFinalColumns = [];
        foreach ($this->getRelationshipsDesiredFinalColumns() as $relationship => $detailsArray)
        {
            $RelationshipsDesiredFinalColumns[$relationship] = $this->getSingleRelationshipDesiredColumns($relationship ,$detailsArray , $this->getModelHasRelationship($relationship) );
        }

        $this->RelationshipsDesiredFinalColumns = $RelationshipsDesiredFinalColumns;
        return $this;
    }

    /**
     * @param string $relationship
     * @param array $relationshipDetailsArray
     * @param Model|null $relationshipOwner
     * @return array|null
     */
    protected function getSingleRelationshipDesiredColumns(string $relationship , array $relationshipDetailsArray , ?Model $relationshipOwner = null) : array | null
    {
        if(!$relationshipOwner){return null;}
        if(!array_key_exists("columns" , $relationshipDetailsArray))
        {
            return $this->FillingColumnsKeyForRelationshipArray($relationshipOwner , $relationship);
        }

        foreach ($relationshipDetailsArray["columns"] as $index => $value)
        {
            if(is_array($value))
            {
                $nestedRelationshipOwner = $this->getRelationshipOwnerModel($relationshipOwner , $relationship ,  $index);
                $relationshipDetailsArray["columns"][$index] = $this->getSingleRelationshipDesiredColumns($index , $value , $nestedRelationshipOwner);
            }
        }
        return $relationshipDetailsArray;
    }

    protected function getRelationshipOwnerModel(Model $relationshipOwner , string $relationship  , string $nestedRelationship ) : Model | null
    {
        /** @var Collection | Model $relationshipModels */
        $relationshipModelOrCollection = $relationshipOwner->{$relationship};
        if(!$relationshipModelOrCollection ) {  return null; }
        if($relationshipModelOrCollection instanceof Collection && $relationshipModelOrCollection->isEmpty()) {  return null; }

        if($relationshipModelOrCollection instanceof Model)
        {
            $relationshipModelOrCollection =  collect([$relationshipModelOrCollection]);
        }
        return $this->getModelHasRelationship($nestedRelationship , $relationshipModelOrCollection);
    }

    protected function FillingColumnsKeyForRelationshipArray( Model $relationshipOwner , string $relationship ) : array | null
    {
        $columns = $this->getAllModelAttributesKeysArray( $relationshipOwner->{$relationship});
        if(!$columns){return null;}

        $relationshipDetailsArray["columns"] = $columns;
        return $relationshipDetailsArray;
    }

    protected function getModelHasRelationship( string $relationship , ?Collection $relationshipOwners = null) : Model | null
    {
        if ($relationshipOwners == null) { $relationshipOwners = $this->DataCollection; }

        foreach ($relationshipOwners as $item)
        {
            if($item->{$relationship} !== null) {return $item;}
        }
        return null;
    }

    protected function getAllModelAttributesKeysArray(Collection | Model | null $modelOrCollection = null) : array | null
    {
        if(!$modelOrCollection){return null;}
        if($modelOrCollection instanceof Collection) {$modelOrCollection = $modelOrCollection->first();}
        return array_keys(
            $modelOrCollection->makeVisible($modelOrCollection->getHidden())->attributesToArray()
        );
    }


}
