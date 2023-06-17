<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Traits;


use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;
use Spatie\QueryBuilder\QueryBuilder;

trait DataCustomizerMethods
{

    protected string $ModelClass;
    protected string $modelPrimaryKeyName;
    protected QueryBuilder $builder;
    protected Collection | LazyCollection | null $DataCollection = null;
    protected int $LoadedRowsMaxLimitBeforeDispatchingJob = 5;

    protected int $dataRowsCount;
    protected array $DataToExport = [];
    protected ?Request $request = null;

    abstract protected function getModelClass() : string;

    //This method is useful to reduce the retrieved column count .... the unnecessary columns must not be retrieved
    //return null when we want to get all columns
    //Note : Don't Forget to include primary and foreign key names when you expect to load a relationship
    abstract protected function getModelSelectingQueryColumns() : array | null;

    //This method is useful to include relationships by eager loading
    //Note : Don't Forget to include primary and foreign key names when you expect to load a relationship
    //Note : You Can set The Relationships Conditions You need in this array without any difference when you call with method
    abstract protected function getWithRelationshipsArray() : array;

    ////This method is useful to filter the rows in Database before retrieve it
    abstract protected function getFiltersArray() : array;

    public function setCustomRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }


    /**
     * @return DataCustomizerMethods|Exporter
     * @throws Exception
     */
    protected function setDefaultModelClass() : self
    {
        $modelClass = $this->getModelClass();
        if(!class_exists($modelClass)){throw new JsonException("The Given Model Class Is Undefined !");}

        $model = new $modelClass;
        if (!$model instanceof Model){throw new JsonException("The Given Model Class Is Not A Model Instance !");}
        $this->setModelPrimaryKeyName($model);
        unset($model);

        $this->ModelClass = $modelClass;

        return $this;
    }


    /**
     * @param Model $model
     * @return DataCustomizerMethods|Exporter
     */
    protected function setModelPrimaryKeyName(Model $model): self
    {
        $this->modelPrimaryKeyName = $model->getKeyName();
        return $this;
    }

    /**
     * @return DataCustomizerMethods|Exporter
     * @throws Exception
     */
    protected function initQueryBuilder() : self
    {
        $this->setDefaultModelClass();
        $this->builder = QueryBuilder::for($this->ModelClass , $this->request)
                                        ->with( $this->getWithRelationshipsArray() )
                                        ->allowedFilters($this->getFiltersArray())
                                        ->datesFiltering()
                                        ->select($this->getModelSelectingQueryColumns() ?? ['*'])
                                        ->customOrdering();
        return $this;
    }

    /**
     * @param int $count
     * @return DataCustomizerMethods|Exporter
     * @throws Exception
     */
    protected function setNeededDataCount(int $count = -1) : self
    {
        if($count < 0 ){$count =  $this->builder->count();}
        if($count == 0 ) { throw $this->getEmptyDataException();}
        $this->dataRowsCount = $count;
        return $this;
    }

    protected function LazyDataById() : void
    {
        $this->DataCollection = $this->builder->lazyById($this->LoadedRowsMaxLimitBeforeDispatchingJob , $this->modelPrimaryKeyName);
    }

    protected function cursorData() : void
    {
        $this->DataCollection = $this->builder->cursor();
    }

    /**
     * @param Collection|null $collection
     * @return DataCustomizerMethods|Exporter
     */
    protected function setDataCollection(?Collection $collection = null) : self
    {
        if($collection != null)
        {
            $this->DataCollection = $collection;
            return $this;
        }

        if($this->dataRowsCount > $this->LoadedRowsMaxLimitBeforeDispatchingJob)
        {
            $this->LazyDataById();
            return $this;
        }

        if(empty($this->getWithRelationshipsArray()))
        {
            $this->cursorData();
            return $this;
        }

        $this->LazyDataById();
        return $this;
    }

    /**
     * @return DataCustomizerMethods|Exporter
     */
    protected function setDefaultDataCollection() : self
    {
        if($this->DataCollection != null){return $this;}
        return $this->setDataCollection();
    }

    /**
     * @param Collection|LazyCollection $DataCollection
     * @return DataCustomizerMethods|Exporter
     * @throws Exception
     * This Method is used to change Exported Data from controller context ... but it is mainly changed
     * by setDefaultData method in the constructor of object
     */
    public function setCustomDataCollection( Collection | LazyCollection $DataCollection ) : self
    {
        return $this->setNeededDataCount($DataCollection->count())
                    ->setDataCollection($DataCollection);
    }

    /**
     * @return DataCustomizerMethods|Exporter
     * @throws JsonException
     */
    protected function setData() : self
    {
        $this->DataToExport =  $this->finalDataArrayProcessor->getProcessedData($this->DataCollection);
        return $this;
    }

    protected function getEmptyDataException() : JsonException
    {
        return new JsonException("Data Array Or Collection Can't Be Empty !") ;
    }

    protected function DoesHaveBigData() : bool
    {
        return $this->dataRowsCount > $this->LoadedRowsMaxLimitBeforeDispatchingJob;
    }

    /**
     * @return DataCustomizerMethods|Exporter
     * @throws JsonException
     */
    protected function setConvenientResponder() : self
    {
        if( $this->MustExportFiles() || $this->SupportRelationshipsFilesExporting() || $this->DoesHaveBigData() )
        {
            return $this->setJobDispatcherJSONResponder();
        }
        return $this->setStreamingResponder();
    }

}
