<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\DataFilesContentProcessors\DataFileContentProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Interfaces\CareAboutDateTruth;
use App\Exceptions\JsonException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DataCustomizerMethods
{

    protected ?DataFileContentProcessor $dataFileContentProcessor = null;
    protected array $ModelDesiredColumns = [];

    protected function setDataFileContentProcessorProps() : DataFileContentProcessor
    {
        return $this->dataFileContentProcessor->setFilesProcessor($this->filesProcessor)
                                              ->setFilePathToProcess($this->getDataFilePath());
    }

    protected function initDataFileContentProcessor() : DataFileContentProcessor
    {
        if(!$this->dataFileContentProcessor){$this->dataFileContentProcessor = $this->getDataFileContentProcessor();}
        return $this->setDataFileContentProcessorProps();
    }

    public function getDataToImport() : array
    {
        return $this->initDataFileContentProcessor()->getData();
    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function throwEmptyDataException() : void
    {
        throw new JsonException("There Is No Data To Import");
    }

    protected function setModelDateColumns(array $columns) : array
    {
        if($this instanceof CareAboutDateTruth)
        {
            return array_merge( $columns , $this->getDateColumns());
        }
        return $columns;
    }

    protected function getModelDBTable() : string
    {
        return app($this->getModelClass())->getTable();
    }
    /**
     * Override It When It Is Needed In Child Class
     * @return array
     */
    protected function getModelDesiredColumns() : array
    {
        return Schema::getColumnListing( $this->getModelDBTable()  );
    }

    protected function setModelDesiredColumns() : self
    {
        $columns = $this->getModelDesiredColumns();
        $this->ModelDesiredColumns = $this->setModelDateColumns($columns);
        return $this;
    }

    protected function getModelDesiredColumnValues(array $dataRow) : array
    {
        $columnsValues = [] ;

        foreach ($this->ModelDesiredColumns as $column)
        {
            if(array_key_exists($column , $dataRow))
            {
                $columnsValues[$column] = $dataRow[$column];
            }
        }
        return $columnsValues;
    }

    protected function importModel(array $row) : Model
    {
        $Model = app($this->getModelClass());
        foreach ($row as $column => $value)
        {
            $Model->{$column} = $value;
        }
        return $Model;
    }

    /**
     * @param array $row
     * @return Model | null
     * @throws JsonException
     */
    protected function importDataRow(array $row) : Model | null
    {
        $this->validateDataRow($row);
        if($this->getModelDesiredColumnValues($row))
        {
            return $this->importModel($row);
        }
        return null;
    }


    /**
     * @throws JsonException
     */
    protected function importData() : Importer
    {
        DB::beginTransaction();
        $this->setModelDesiredColumns();
        foreach ($this->ImportedDataArray as $row)
        {
            $this->importDataRow($row);
        }
        return $this;
    }



}
