<?php


namespace App\CustomLibs\ExportersManagement\Exporter;

use App\CustomLibs\ExportersManagement\Exporter\Traits\DataCustomizerMethods;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use App\CustomLibs\ExportersManagement\Interfaces\NeedExtraQueryConditions;
use App\Exceptions\JsonException;
use App\Jobs\ExportersManagementJobs\HugeDataExporterJob;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Exporter
{
    use DataCustomizerMethods  ;

    protected string $fileName ;
    protected string $title;
    protected ?DataArrayProcessor $finalDataArrayProcessor = null;

    abstract protected function exportingFun();
    //: StreamedResponse;
    abstract protected function getDocumentTitle() : string;
    abstract protected function getFinalDataArrayProcessor() : DataArrayProcessor;

    /**
     * @return array
     *
     * Result Array will be like [ Relationship => Details Array ]
     *
     * Don't Forget To Include The Relationship in with relationships array
    otherwise the relationship will be loaded dynamically by single query for each model's relationship

     * Details Array will be like
    [
    "columns" => [columns and sub relationships array] ,
    "columns_prefix" => "column prefix resetting value , if it is not set the relation name will be used as prefix"
    ]
     */
    abstract protected function getRelationshipsDesiredFinalColumns() : array ;


    /**
     * @return array
     * This method is useful to determine the desired columns of model
     * Note  : that if the result is an empty array ... That means we want all retrieved columns of the model (not all actual columns ... ONLY Retrieved Columns)
     */
    abstract protected function getModelDesiredFinalColumns() : array  ;

    /**
     * @return $this
     */
    protected function setFinalDataArrayProcessor() : self
    {
        if($this->finalDataArrayProcessor) {return $this;}
        $this->finalDataArrayProcessor = $this->getFinalDataArrayProcessor();
        $this->finalDataArrayProcessor->setModelDesiredFinalDefaultColumnsArray($this->getModelDesiredFinalColumns());
        if($this->finalDataArrayProcessor instanceof HasRelationshipsDesiredColumns)
        {
            $this->finalDataArrayProcessor->setRelationshipsDefaultDesiredFinalColumnsArray($this->getRelationshipsDesiredFinalColumns());
        }
        return $this;
    }


    /**
     * @param callable $mappingFun
     * @return $this
     */
    public function mapOnFinalDataArray(callable $mappingFun) : self
    {
        $this->finalDataArrayProcessor->setFinalDataArrayMappingFun($mappingFun);
        return $this;
    }


    /**
     * @throws Exception
     */
    public function __construct()
    {

    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function initExporter() : self
    {
        $this->setDefaultFileName();

        if( $this->DataCollection == null)
        {
            $this->initQueryBuilder();
            if($this instanceof NeedExtraQueryConditions) { $this->setQueryConditionsOnBuilder(); }
            $this->setNeededDataCount();
        }
        return $this;
    }

    public function getFileName() : string
    {
        return Str::slug($this->getDocumentTitle() , "_");
    }


    /**
     * @return $this
     */
    protected function setDefaultFileName() : self
    {
        $this->fileName =  $this->getFileName();
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     * This Method is used to change file name from controller context ... but it is mainly changed by child class
     * by getFileName method in the constructor of object
     */
    public function setCustomFileName(string $name = "") : self
    {
        if($name === ""){throw new JsonException("Exported File Name Can't Be Null Or empty String !");}
        $this->fileName = $name ;
        return $this;
    }

    /**
     * @return StreamedResponse
     * @throws Exception
     */
    public function exportingFunAliasForJob() : StreamedResponse
    {
        $this->setDefaultDataCollection()->setData();
        return $this->exportingFun();
    }


    /**
     * @return JsonResponse|StreamedResponse
     * @throws JsonException | Exception
     */
    public function export() : JsonResponse | StreamedResponse
    {
        try {
            $this->initExporter();
            if($this->DoesItNeedToBackgroundProcessing())
            {
                $job = new HugeDataExporterJob(get_class($this) , request());
                if($this->DataCollection != null)
                {
                    $job->setDataCollection($this->DataCollection);
                }
                dispatch($job);

                return Response::success([] , ["The Needed Data Is In Large Size , You Will Receive The Needed Data Files On Your Email !"]);
            }


            //Needed Other Setup Operations
            $this->setDefaultDataCollection()
                 ->setFinalDataArrayProcessor();

            $this->setData();
            return Response::success($this->DataToExport);
            return $this->exportingFun();

        }catch(Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }

}
