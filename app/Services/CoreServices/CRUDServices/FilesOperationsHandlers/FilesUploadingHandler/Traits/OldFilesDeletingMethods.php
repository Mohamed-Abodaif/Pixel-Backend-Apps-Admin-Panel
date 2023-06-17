<?php

namespace App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits;


use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use App\Services\CoreServices\CRUDServices\Jobs\OldFilesDeleterJob;
use App\Services\CoreServices\CRUDServices\OldFilesInfoManager\OldFilesInfoManager;

trait OldFilesDeletingMethods
{
    protected function initOldFilesInfoManager() : OldFilesInfoManager
    {
        if(!$this->oldFilesInfoManager){$this->oldFilesInfoManager = new OldFilesInfoManager();}
        return $this->oldFilesInfoManager;
    }

    protected function setOldFileToDeletingQueue(string $fileName , string $folderName) : bool
    {
        $fileRelevantPath = $folderName . "/" . $fileName;
        if(!$this->initOldFilesInfoManager()->addOldFileInfo($fileName , $fileRelevantPath)){return false;}
        $this->oldFilesInfoManager->SaveChanges();

        $this->dispatchDeleterJob();
        return true;
    }

    protected function dispatchDeleterJob() : void
    {
        $deleterJob = new OldFilesDeleterJob();
        dispatch($deleterJob);
    }

}
