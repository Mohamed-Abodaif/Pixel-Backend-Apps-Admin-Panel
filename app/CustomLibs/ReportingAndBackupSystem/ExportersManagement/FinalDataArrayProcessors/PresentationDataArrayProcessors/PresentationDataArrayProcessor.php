<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\PresentationDataArrayProcessors;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterStringValueProcessor\ExporterStringValueProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\RelationshipsSupporterDataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PresentationDataArrayProcessor extends RelationshipsSupporterDataArrayProcessor
{
    protected ExporterStringValueProcessor $processor;

    public function __construct()
    {
        $this->processor = new ExporterStringValueProcessor();
    }

    protected function processModelSingleDesiredColumns(string $column  ,Model $model ,array $row = []) : array
    {
        $row[ $this->processor->processPresentationExporterCapitalizeKey($column) ]
            =
            $this->processor->processPresentationExporterNullValue($this->getObjectKeyValue($column, $model ));
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
        $row[$relationship] = $this->processor->processPresentationExporterNullValue() ;
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
                continue;
            }
            /**
             * $index is column index (numeric number)
             * $value is the column name
             */
            $dataRowArray = $this->processModelRelationshipsSingleDesiredColumn(  $relationship , $value , $model  , $dataRowArray , $relationshipDetailsArray["columns_prefix"] ?? null );
        }
        return $dataRowArray;
    }

    protected function processModelRelationshipsSingleDesiredColumn( string $relationship ,  string | array $columns ,   ?Model $model = null ,array $row = [] , string | null $relationshipNamePrefix = null ) : array
    {
        $row[ $this->processor->processPresentationExporterCapitalizeKey($columns , $relationshipNamePrefix ?? $relationship) ]
            =
            $this->processor->processPresentationExporterNullValue(
                $this->getObjectKeyValue($columns , $model?->{$relationship}  )
            );
        return $row;
    }

    protected function getArrayKeysValues(array $keys , Model | Collection | array  $array) : array
    {
        $values = [];
        foreach ($keys as $key)
        {
            if(array_key_exists($key , $array))
            {
                $values[$key] = $array[$key];
            }
        }
        return $values;
    }

    protected function getArrayKeyValue(string $key , Model | Collection | array  $array) : string | null
    {
        return $array[$key] ?? null;
    }

}
