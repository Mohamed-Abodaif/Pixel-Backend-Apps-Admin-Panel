<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\RelationshipsSupporterDataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use Illuminate\Database\Eloquent\Model;

class ChildRelationshipsContainerArrayProcessor extends RelationshipsSupporterDataArrayProcessor
{
    protected function processModelSingleDesiredColumns(string $column , Model $model ,array $row = []) : array
    {
        $row[ $column ] =  $this->getObjectKeyValue($column, $model );
        return $row;
    }

    /**
     * @param array $RelationshipsDesiredFinalColumns
     * @return $this
     */
    public function setRelationshipsDefaultDesiredFinalColumnsArray( array $RelationshipsDesiredFinalColumns = []): HasRelationshipsDesiredColumns
    {
        $this->RelationshipsDesiredFinalColumns = $RelationshipsDesiredFinalColumns;
        return $this;
    }

    protected function processModelRelationshipEmptyValue(string $relationship  , array $row = []) : array
    {
        $row[$relationship] = null ;
        return $row;
    }

    protected function processModelSingleRelationshipDesiredColumnsInternalFun( string $relationship , null | array $relationshipDetailsArray  ,?Model $model = null   , array $dataRowArray = []) : array
    {
        foreach ($relationshipDetailsArray["columns"] as $index => $value)
        {
            if(is_array($value))
            {
                /**
                 *  if $value is an array ----> there is need to load the nested relationship's columns
                    $index is the nested relationship name
                    $value is the nested relationship details array
                 */
                $dataRowArray = $this->processModelSingleRelationshipDesiredColumns( $index ,  $value , $model?->{$relationship} , $dataRowArray);
                unset($relationshipDetailsArray["columns"][$index]);
            }
        }
        return  $this->processModelRelationshipsSingleDesiredColumn(  $relationship ,  $relationshipDetailsArray["columns"] ,  $model  , $dataRowArray);
    }

    protected function processModelRelationshipsSingleDesiredColumn( string $relationship ,  string | array $columns ,   ?Model $model = null ,array $row = [] , string | null $relationshipNamePrefix = null ) : array
    {
        $row[$relationship] = $this->getObjectKeysValues($columns, $model?->{$relationship}  );
        return $row;
    }

}
