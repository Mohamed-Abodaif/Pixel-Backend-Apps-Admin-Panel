<?php

namespace App\Services\CoreServices\CRUDServices\RelationshipsHandlers\Traits;

use App\Services\CoreServices\CRUDServices\Interfaces\ParticipatesToRelationships;
use Illuminate\Database\Eloquent\Model;

trait ParticipatingRelationshipMethods
{

    abstract protected function ParticipatingRelationshipRowsChildClassHandling(Model $model , string $relationshipName ,array $ParticipatingRelationshipMultipleRows ) : bool;

    protected function getParticipatingRelationshipRow(array $dataRow , string $foreignColumnName , array $pivotColumns , array $arrayToOverride = []) : array
    {
        if(!array_key_exists($foreignColumnName , $dataRow)){return $arrayToOverride;}

        $foreignColumnValue = $dataRow[$foreignColumnName];
        $pivotColumnsValues = [];

        foreach ($pivotColumns as $column)
        {
            if(array_key_exists( $column , $dataRow))
            {
                $pivotColumnsValues[$column] = $dataRow[$column];
            }
        }
        $arrayToOverride[$foreignColumnValue] = $pivotColumnsValues;
        return  $arrayToOverride ;
    }

    protected function getParticipatingRelationshipRows(array $dataRow ,  string $relationshipName , array $relationshipInfo , Model $model) : array | null
    {
        $foreignColumnName = $relationshipInfo["foreignColumnName"];
        $pivotColumns = $relationshipInfo["pivotColumns"];
        $rows = [];

        $RelationshipDataRows = $this->getRelationshipRequestData( $dataRow ,   $relationshipName , $model);

        foreach ($RelationshipDataRows as $dataRow)
        {
            $rows = $this->getParticipatingRelationshipRow($dataRow , $foreignColumnName , $pivotColumns , $rows);
        }
        return $rows;
    }

    protected function HandleParticipatingRelationshipRows( Model $model , string $relationshipName, array $relationshipInfo , array $dataRow ) : self
    {
        $ParticipatingRelationshipMultipleRows = $this->getParticipatingRelationshipRows($dataRow , $relationshipName , $relationshipInfo , $model);
        $this->ParticipatingRelationshipRowsChildClassHandling($model , $relationshipName ,$ParticipatingRelationshipMultipleRows );
        return $this;
    }

    protected function getValidParticipatingRelationshipNameArray(string | int $relationshipName , array $array) : array | null
    {
        if(is_numeric($relationshipName)){return null;}
        if(!array_key_exists("foreignColumnName" , $array)){return null;}
        if(!array_key_exists("pivotColumns" , $array)){ $array["pivotColumns"] = [];}
        return $array;
    }
    protected function HandleModelParticipatingRelationships(array $dataRow , Model $model) : self
    {
        if(!$this::DoesItParticipateToRelationships($model)){ return $this ;}

        /**@var Model | ParticipatesToRelationships $model*/
        foreach ($model->getParticipatingRelationshipNames() as $relationshipName => $relationshipInfo)
        {
            $relationshipInfo = $this->getValidParticipatingRelationshipNameArray($relationshipName , $relationshipInfo);
            if($relationshipInfo)
            {
                $this->HandleParticipatingRelationshipRows($model, $relationshipName, $relationshipInfo, $dataRow);
            }
        }
        return $this;
    }


}
