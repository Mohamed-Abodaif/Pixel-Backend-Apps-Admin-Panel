<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits\DataArrayMappingMethodsTrait;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits\ModelDesiredColumnsValidator;
use App\Exceptions\JsonException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

abstract class DataArrayProcessor
{
    use DataArrayMappingMethodsTrait , ModelDesiredColumnsValidator ;

    protected array $ModelDesiredFinalColumns = [];
    protected Collection | LazyCollection | null $DataCollection = null;

    abstract protected function processModelSingleDesiredColumns( string $column  , Model $model ,array $row = []) : array;

    /**
     * @param array $keys
     * @param Model|Collection|array|null $object
     * @return array|null
     * Used To get Associative Array
     */
    protected function getObjectKeysValues(array $keys ,  Model | Collection | array | null $object = null) : array | null
    {
        if(!$object ){return null;}
        if(is_array($object))
        {
            /** * @var array $object */
            return array_intersect_key($object, array_flip($keys));
        }

        if($object instanceof Model)
        {
            /** * @var Model | Collection $object */
            return $object->only($keys);
        }

        return $object->map(function ($row) use ( $keys)
        {
            return $row->only($keys);
        })->toArray();
    }

    /**
     * @param string|array $keyName
     * @param Model|Collection|array|null $object
     * @return string|null
     * USed To get String value
     */
    protected function getObjectKeyValue(string | array $keyName , Model | Collection | array | null $object) : string | null
    {
        if(!$object ){return null;}
        if($object instanceof Model) { return  $object->{$keyName}; }

        //If Object is An array
        if(is_array($object)) { return $object[$keyName] ?? null; }

        //If Object Is a Collection
        return $object->map(function ($row) use ($keyName)
        {
            if($row->{$keyName}){return $row->{$keyName};}
        })->join(" , ");
    }

    /**
     * @param array $ModelDesiredFinalColumns
     * @return $this
     */
    public function setModelDesiredFinalDefaultColumnsArray(array $ModelDesiredFinalColumns = []): self
    {
        $this->ModelDesiredFinalColumns = $ModelDesiredFinalColumns;
        return $this;
    }

    /**
     * @param Model $model
     * @param array $row
     * @return array
     */
    protected function processModelDesiredColumns(Model $model ,array $row = []) : array
    {
        foreach ($this->ModelDesiredFinalColumns as $column)
        {
            $row = $this->processModelSingleDesiredColumns($column, $model , $row );
        }
        return $row;
    }

    /**
     * @param Collection|LazyCollection $DataCollection
     * @return $this
     */
    public function setDataCollection(Collection|LazyCollection $DataCollection): self
    {
        $this->DataCollection = $DataCollection;
        return $this;
    }

    /**
     * @return Collection|LazyCollection|null
     */
    public function getDataCollection(): Collection|LazyCollection|null
    {
        return $this->DataCollection;
    }

    protected function DataSetup(Collection | LazyCollection $collection ) : self
    {
        $this->setDataCollection($collection)
             ->setModelDesiredFinalDefaultColumns();

        return $this;
    }
    /**
     * @param Collection|LazyCollection $collection
     * @return array
     * @throws JsonException
     */
    public function getProcessedData(Collection | LazyCollection $collection  ): array
    {
        $this->DataSetup($collection);

        $finalData = [];
        foreach ($this->getDataCollection() as $model)
        {
            $row = $this->processDataRow($model);
            if(!empty($row)){ $finalData[] = $row; }
        }
        return $this->callMappingFunOnRowsArray($finalData);
    }

    protected function processDataRow(Model $model) : array
    {
        return  $this->processModelDesiredColumns($model);
    }
}
