<?php

namespace App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors;


//Handling Relationships the model belongs to
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use Illuminate\Database\Eloquent\Model;

class ParentRelationshipsContainerArrayProcessor extends DataArrayProcessor
{
    protected function processModelSingleDesiredColumns(string $column , Model $model ,array $row = []) : array
    {
        $row[ $column ] =  $this->getObjectKeyValue($column, $model );
        return $row;
    }
}
