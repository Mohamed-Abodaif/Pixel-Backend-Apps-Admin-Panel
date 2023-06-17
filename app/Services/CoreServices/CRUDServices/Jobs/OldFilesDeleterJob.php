<?php

namespace App\Services\CoreServices\CRUDServices\Jobs;

use App\CustomLibs\CustomFileSystem\CustomFileDeleter;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileDeleter\S3CustomFileDeleter;
use App\Services\CoreServices\CRUDServices\OldFilesInfoManager\OldFilesInfoManager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class OldFilesDeleterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   protected ?CustomFileDeleter $customFileDeleter = null;
   protected ?OldFilesInfoManager $oldFilesInfoManager = null;
   protected array $successfullyDeletedFilesNames = [];

    /**
     * @return $this
     */
    public function initUpdatedFilesInfoManager(): self
    {
        if($this->oldFilesInfoManager){return $this;}
        $this->oldFilesInfoManager = new OldFilesInfoManager();
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

        foreach ($this->oldFilesInfoManager->getAllFiles() as $fileName => $fileRelevantPath)
        {
            if($this->customFileDeleter->deleteFileIfExists( $fileRelevantPath ) )
            {
                $this->successfullyDeletedFilesNames[] = $fileName;
            }
        }
        return $this->informOldFilesInfoManager();
    }

    /**
     * @return $this
     */
    protected function informOldFilesInfoManager() : self
    {
        foreach ($this->successfullyDeletedFilesNames as $fileName)
        {
            $this->oldFilesInfoManager->removeFileInfo($fileName)->SaveChanges();
        }
        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->initCustomFileDeleter()->initUpdatedFilesInfoManager()->DeleteMustDeletedFiles();
    }
}
