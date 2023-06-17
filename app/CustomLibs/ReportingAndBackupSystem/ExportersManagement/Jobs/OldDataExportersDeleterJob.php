<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Jobs;

use App\CustomLibs\CustomFileSystem\CustomFileDeleter;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileDeleter\S3CustomFileDeleter;
use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ExportedDataFilesInfoManager\ExportedDataFilesInfoManager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class OldDataExportersDeleterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


   protected ?CustomFileDeleter $customFileDeleter = null;
   protected ?ExportedDataFilesInfoManager $dataFilesInfoManager = null;
   protected array $successfullyDeletedFilesNames = [];

    /**
     * @return $this
     */
    public function initDataFilesInfoManager(): self
    {
        if($this->dataFilesInfoManager){return $this;}
        $this->dataFilesInfoManager = new ExportedDataFilesInfoManager();
        return $this;
    }

    protected function initCustomFileDeleter() : self
    {
        if($this->customFileDeleter){return $this;}
        $this->customFileDeleter = new S3CustomFileDeleter();
        return $this;
    }

    /**
     * @return $this
     */
    protected function DeleteMustDeletedFiles() : self
    {

        foreach ($this->dataFilesInfoManager->getExpiredFileNamesWithRelevantPath() as $fileInfo)
        {
            if($this->customFileDeleter->deleteFileIfExists( $fileInfo["fileRelevantPath"] ) )
            {
                $this->successfullyDeletedFilesNames[] = $fileInfo["fileName"];
            }
        }
        return $this->informDataFilesInfoManager();
    }

    /**
     * @return $this
     */
    protected function informDataFilesInfoManager() : self
    {
        $this->dataFilesInfoManager->removeExpiredFilesInfo($this->successfullyDeletedFilesNames)
                                   ->SaveChanges();
        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->initCustomFileDeleter()->initDataFilesInfoManager()->DeleteMustDeletedFiles();
    }
}
