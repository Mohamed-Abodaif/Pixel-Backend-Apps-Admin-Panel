<?php


namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter;

use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use App\CustomLibs\ReportingAndBackupSystem\ExportedDataFilesInfoManager\ExportedDataFilesInfoManager;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\ExportedFilesProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Traits\DataCustomizerMethods;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Traits\ExporterAbstractMethods;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces\MustExportFiles;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces\SupportRelationshipsFilesExporting;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\HasRelationshipsDesiredColumns;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\NeedExtraQueryConditions;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\JobDispatcherJSONResponder;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\Responder;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Exporter
{
    use DataCustomizerMethods  , ExporterAbstractMethods;

    /**
     * @var string
     * Without Extension
     */
    protected string $fileName ;

    /**
     * @var string
     * With Extension
     */
    protected string $fileFullName ;

    /**
     * @var string
     * Final File Which Be Uploaded To Storage (Data File OR Zip File If it Needs To A Compression)
     */
    protected string $finalFilePath ;

    protected string $title;
    protected ?DataArrayProcessor $finalDataArrayProcessor = null;
    protected ?ExportedFilesProcessor $filesProcessor = null;
    protected Responder $responder;


    /**
     * @return $this
     */
    protected function setFilesProcessor(): self
    {
        if($this->filesProcessor){return $this;}
        $this->filesProcessor = new ExportedFilesProcessor();
        return $this;
    }

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

    protected function MustExportFiles() : bool
    {
        return $this instanceof MustExportFiles;
    }

    protected function SupportRelationshipsFilesExporting() : bool
    {
        return $this instanceof SupportRelationshipsFilesExporting;
    }


    protected function getJobDispatcherJSONResponder() : JobDispatcherJSONResponder
    {
        return new JobDispatcherJSONResponder();
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function setJobDispatcherJSONResponder() : self
    {
        $this->responder = $this->getJobDispatcherJSONResponder();
        $this->responder->setExporterClass($this)->setDataCollection($this->DataCollection);
        return $this->setResponderGeneralProps();
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function setStreamingResponder() : self
    {
        $this->PrepareExporterData();
        $this->responder = $this->getStreamingResponder();
        $this->responder->setDataToExport($this->DataToExport)
                        ->setFileFullName($this->fileFullName);
        return $this->setResponderGeneralProps()->setStreamingResponderResponseProps();
    }

    /**
     * Overwrite it on need in child class
     * @return $this
     */
    protected function setResponderGeneralProps() : self
    {
        return $this;
    }

    /**
     * Overwrite it on need in child class
     * @return $this
     */
    protected function setStreamingResponderResponseProps() : Exporter
    {
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
    public function __construct() {}

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

    /**
     * return Only File Name (Document Title + Date  , Doesn't Contain The Extension )
     *To Get Full name With Extension use $this->fileName
     * @return string
     */
    public function getFileName() : string
    {
        return Str::slug( $this->getDocumentTitle() , "_") .  date("_Y_m_d_his") ;
    }

    /**
     * @return $this
     */
    protected function setDefaultFileName() : self
    {
        $this->fileName =  $this->getFileName() ;
        $this->fileFullName =  $this->fileName . "." . $this->getDataFileExtension();
        return $this;
    }

    /**
     * @param string $name
     * @param string $extension
     * @return $this
     * @throws Exception
     * This Method is used to change file name from controller context ... but it is mainly changed by child class
     * by getFileName method in the constructor of object
     */
    public function setCustomFileName(string $name , string $extension) : self
    {
        $this->fileName = $name  ;
        $this->fileFullName = $name . "." . $extension;
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function PrepareExporterData() : self
    {
        return  $this->setDefaultDataCollection()
                     ->setFinalDataArrayProcessor()
                     ->setData();
    }


    protected function processDataFilePath(string $DataFileContainerFolderPath) : string
    {
        return CustomFileHandler::processFolderPath($DataFileContainerFolderPath) . $this->fileFullName;
    }

    protected function generateFileFinalURL(string $fileName) : string
    {
        return URL::temporarySignedRoute(
                "exported-file-downloading" ,
                      now()->addDays(ExportedDataFilesInfoManager::ValidityIntervalDayCount)->getTimestamp() ,
                     ["fileName" => $fileName]
                );
    }

    /**
     * @return string
     * Returns Final File's Path In storage
     * @throws Exception
     */
    protected function uploadFinalFile() : string
    {
        return $this->filesProcessor->ExportedFilesStorageUploading($this->finalFilePath);
    }

    /**
     * @return string
     * @throws JsonException
     * @throws Exception
     */
    protected function exportingJobAllDataAndFilesFun() : string
    {
        return $this->initExporter()
                    ->PrepareExporterData()
                    ->setFilesProcessor()
                    ->processDataFilePath(
                        $this->setDataFileToExportedFilesProcessor()
                    );
    }

    /**
     * @return string
     * @throws Exception
     */
    public function exportingJobFun() : string
    {
        $this->finalFilePath = $this->exportingJobAllDataAndFilesFun();
        $this->uploadFinalFile();
        return $this->generateFileFinalURL(
                    $this->filesProcessor->getFileDefaultName($this->finalFilePath)
                );
    }


    /**
     * @return JsonResponse|StreamedResponse
     * @throws JsonException | Exception
     */
    public function export() : JsonResponse | StreamedResponse
    {
        try {
            $this->initExporter()->setConvenientResponder();
            return $this->responder->respond();
        }catch(Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }

}
