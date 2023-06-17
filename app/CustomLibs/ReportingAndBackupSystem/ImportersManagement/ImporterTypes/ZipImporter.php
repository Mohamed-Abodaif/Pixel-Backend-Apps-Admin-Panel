<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\ImporterTypes;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use ZipArchive;

abstract class ZipImporter extends Importer
{

    protected ZipArchive $zip;
    protected ?Importer $DataImporter = null;

    abstract protected function getDataImporterTypes() : array;

    protected function setDataImporterProps() : Importer
    {

    }

    protected function getConvenientDataImporter() : Importer
    {

    }
    protected function initDataImporter() : Importer
    {
//        if(!$this->DataImporter){$this->DataImporter = new }
        return $this->setDataImporterProps();
    }

    protected function initZipFile()
    {
        $this->zip = new ZipArchive();
        $this->zip->open(
            $this->uploadedFile->getRealPath(),
            ZipArchive::CREATE || ZipArchive::OVERWRITE
        );
    }

    public function getDataToImport(): array
    {
        return $this->initDataImporter()->getDataToImport();
    }



}
