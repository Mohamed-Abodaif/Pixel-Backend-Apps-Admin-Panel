<?php

namespace App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors;

use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use Illuminate\Database\Eloquent\Model;

abstract class ImportingDataArrayProcessor extends DataArrayProcessor
{

    protected function processModelRelationshipEmptyValue(string $relationship  , array $row = []) : array
    {
        $row[$relationship] = null ;
        return $row;
    }

    protected function processModelRelationshipsSingleDesiredColumn( string $relationship , array $relationshipDetailsArray , string $column ,  ?Model $model = null ,array $row = []) : array
    {
        $row[ $column ] = $this->getObjectKeyValue($column , $model?->{$relationship}  );
        return $row;
    }

    protected function processModelSingleDesiredColumns(string $column , Model $model ,array $row = []) : array
    {
        $row[ $column ] =  $this->getObjectKeyValue($column, $model );
        return $row;
    }
}
