<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits\RelationshipsDesiredColumnsValidators;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

abstract class RelationshipsSupporterDataArrayProcessor extends DataArrayProcessor implements HasRelationshipsDesiredColumns
{
    use RelationshipsDesiredColumnsValidators;
    protected array $RelationshipsDesiredFinalColumns = [];

    abstract protected function processModelRelationshipEmptyValue(string $relationship  , array $row = []) : array ;
    abstract protected function processModelSingleRelationshipDesiredColumnsInternalFun( string $relationship , null | array $relationshipDetailsArray  ,?Model $model = null   , array $dataRowArray = []) : array;
    abstract protected function processModelRelationshipsSingleDesiredColumn( string $relationship ,  string | array $columns ,   ?Model $model = null ,array $row = [] , string | null $relationshipNamePrefix = null ) : array;


    /**
     * @param Model $model
     * @param array $row
     * @return array
     */
    protected function processModelRelationshipsDesiredColumns(Model $model , array $row = []) : array
    {
        foreach ($this->RelationshipsDesiredFinalColumns as $relationship => $details)
        {
            $row = $this->processModelSingleRelationshipDesiredColumns( $relationship , $details ,$model , $row);
        }
        return $row;
    }

    /**
     * @param string $relationship
     * @param array|null $relationshipDetailsArray
     * @param Model|null $model
     * @param array $dataRowArray
     * @return array
     */
    protected function processModelSingleRelationshipDesiredColumns( string $relationship , null | array $relationshipDetailsArray  ,?Model $model = null   , array $dataRowArray = []) : array
    {
        if(!$relationshipDetailsArray)
        {
            return $this->processModelRelationshipEmptyValue( $relationship  , $dataRowArray);
        }
        return $this->processModelSingleRelationshipDesiredColumnsInternalFun(  $relationship , $relationshipDetailsArray  ,$model ,$dataRowArray );
    }


    protected function DataSetup(Collection | LazyCollection $collection ) : self
    {
        parent::DataSetup($collection);
        $this->setRelationshipsDefaultDesiredFinalColumns();

        return $this;
    }

    protected function processDataRow(Model $model): array
    {
        return $this->processModelRelationshipsDesiredColumns($model , parent::processDataRow($model));
    }
}
