<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\DataImporterJob;
use App\Exceptions\JsonException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait JobSerializingMethods
{
    private string $importerClass;
    protected string $importedDataFileStoragePath = "";
    protected bool $DeleteImportedDataFileAfterProcessing = false;
    private Authenticatable | User $notifiable;

    /**
     * @param string $importedDataFileStoragePath
     * @return DataImporterJob
     */
    public function setImportedDataFileStoragePath(string $importedDataFileStoragePath): DataImporterJob
    {
        $this->importedDataFileStoragePath = $importedDataFileStoragePath;
        return $this;
    }

    /**
     * @param bool $DeleteImportedDataFileAfterProcessing
     * @return DataImporterJob
     */
    public function informToDeleteImportedDataFileAfterProcessing(bool $DeleteImportedDataFileAfterProcessing): DataImporterJob
    {
        $this->DeleteImportedDataFileAfterProcessing = $DeleteImportedDataFileAfterProcessing;
        return $this;
    }

    /**
     * @param string $importerClass
     * @return DataImporterJob
     * @throws JsonException
     */
    private function setImporterClass(string $importerClass) : DataImporterJob
    {
        if(!class_exists($importerClass)){throw new JsonException("The Given Importer Class Is Not Defined !");}

        $importer = new $importerClass();
        if(!$importer instanceof Importer){throw new JsonException("The Given Importer Class Is Not Valid Importer Class !");}
        $this->importerClass = $importerClass ;

        return $this;
    }


    private function setNotifiable() : self
    {

        $this->notifiable = User::where("email" , "aldroubim7@gmail.com")->first();
//        $this->notifiable = auth("api")->user();
        return $this;
    }
}
