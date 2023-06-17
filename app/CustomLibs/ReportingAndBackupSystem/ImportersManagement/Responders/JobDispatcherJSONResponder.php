<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Responders;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\DataImporterJob;
use App\Exceptions\JsonException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class JobDispatcherJSONResponder  extends Responder
{
    protected string $importerClass = "";
    protected ?DataImporterJob $job = null;

    /**
     * @return $this
     * @throws JsonException
     */
    protected function initJob() : self
    {
        if($this->job){return $this;}
        if(!$this->importerClass){throw new JsonException("There Is No Importer Class Given To Job Object");}
        $this->job = new DataImporterJob($this->importerClass );
        return $this;
    }

    /**
     * @param Importer $importerClass
     * @return $this
     * @throws JsonException
     */
    public function setImporterClass(Importer $importerClass): self
    {
        $this->importerClass = get_class($importerClass);
        $this->initJob();
        return $this;
    }

    /**
     * @param string $importedDataFileStoragePath
     * @return $this
     * @throws JsonException
     */
    public function setImportedDataFileStoragePath(string $importedDataFileStoragePath  ): self
    {
        $this->initJob();
        $this->job->setImportedDataFileStoragePath($importedDataFileStoragePath);
        return $this;
    }

    /**
     * @param bool $DeleteImportedDataFileAfterProcessing
     * @return $this
     * @throws JsonException
     */
    public function informDeleteToImportedDataFileAfterProcessing(bool $DeleteImportedDataFileAfterProcessing): self
    {
        $this->initJob();
        $this->job->informToDeleteImportedDataFileAfterProcessing($DeleteImportedDataFileAfterProcessing);
        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function respond(): JsonResponse
    {
        dispatch($this->job);
        return Response::success([] , ["Your Data File Has Been Uploaded Successfully ! , You Will Receive Your Request Result By Mail Message On Your Email !"]);
    }

}
