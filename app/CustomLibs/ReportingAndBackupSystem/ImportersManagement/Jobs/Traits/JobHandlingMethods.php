<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\DataImporterJob;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Notifications\SuccessfulImportingNotification;
use App\Exceptions\JsonException;

trait JobHandlingMethods
{

    private ?Importer $importer = null;

    /**
     * @throws JsonException
     */
    private function setImporterProps() : Importer
    {
        return $this->importer->setUploadedFileStorageRelevantPath($this->importedDataFileStoragePath);
    }

    /**
     * @return Importer
     * @throws JsonException
     */
    private function initImporter() : Importer
    {
        if(!$this->importer){$this->importer = new $this->importerClass;}
        return $this->setImporterProps();
    }

    /**
     * @return DataImporterJob
     */
    protected function SuccessfullyImportingDataNotifier( ) : DataImporterJob
    {
        $this->notifiable->notify(new SuccessfulImportingNotification());
        return $this;
    }
}
