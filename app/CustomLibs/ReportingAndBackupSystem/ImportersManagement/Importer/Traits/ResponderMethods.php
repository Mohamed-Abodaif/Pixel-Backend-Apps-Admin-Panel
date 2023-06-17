<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Responders\JobDispatcherJSONResponder;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Responders\Responder;
use App\Exceptions\JsonException;

trait ResponderMethods
{

    protected ?Responder $responder = null;

    protected function getJobDispatcherResponder() :  JobDispatcherJSONResponder
    {
        return new JobDispatcherJSONResponder();
    }

    /**
     * @throws JsonException
     */
    protected function setResponderProps() : Responder
    {
        return $this->responder->setImporterClass($this)
                               ->setImportedDataFileStoragePath($this->uploadedFileStorageRelevantPath );
    }
    /**
     * @throws JsonException
     */
    protected function initResponder() : Responder
    {
        if(!$this->responder){$this->responder = $this->getJobDispatcherResponder();}
        return $this->setResponderProps();
    }
}
