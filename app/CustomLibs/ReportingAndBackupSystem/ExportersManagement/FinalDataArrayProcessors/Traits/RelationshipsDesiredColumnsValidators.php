<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\RelationshipsSupporterDataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait RelationshipsDesiredColumnsValidators
{
    use DesiredColumnsValidatorsGeneralMethods;

    protected function DoesItHaveRelationshipsDesiredColumns() : bool
    {
        return $this instanceof HasRelationshipsDesiredColumns;
    }

    /**
     * @return RelationshipsSupporterDataArrayProcessor
     */
    protected function setRelationshipsDefaultDesiredFinalColumns() : RelationshipsSupporterDataArrayProcessor
    {
        if(!$this->DoesItHaveRelationshipsDesiredColumns()) { return $this; }
        $RelationshipsDesiredFinalValidColumns = [];
        foreach ($this->RelationshipsDesiredFinalColumns as $relationship => $detailsArray)
        {
            $RelationshipsDesiredFinalValidColumns[$relationship] = $this->getSingleRelationshipDesiredColumns($relationship ,$detailsArray , $this->getModelHasRelationship($relationship) );
        }

        $this->RelationshipsDesiredFinalColumns = $RelationshipsDesiredFinalValidColumns;
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

    /**
     * @param Model $relationshipOwner
     * @param string $relationship
     * @param string $nestedRelationship
     * @return Model|null
     */
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

    /**
     * @param Model $relationshipOwner
     * @param string $relationship
     * @return array|null
     */
    protected function FillingColumnsKeyForRelationshipArray( Model $relationshipOwner , string $relationship ) : array | null
    {
        $columns = $this->getModelOrCollectionAllAttributesKeysArray( $relationshipOwner->{$relationship});
        if(!$columns){return null;}

        $relationshipDetailsArray["columns"] = $columns;
        return $relationshipDetailsArray;
    }

    /**
     * @param string $relationship
     * @param Collection|null $relationshipOwners
     * @return Model|null
     */
    protected function getModelHasRelationship( string $relationship , ?Collection $relationshipOwners = null) : Model | null
    {
        if ($relationshipOwners == null) { $relationshipOwners = $this->getDataCollection(); }

        foreach ($relationshipOwners as $item)
        {
            if($item->{$relationship} !== null) {return $item;}
        }
        return null;
    }



}
