<?php

namespace App\CustomLibs\TemporaryFilesHandler\TemporaryFilesProcessors;


use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesHandler;
use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesProcessors\Traits\TemporaryFileUploadingTrait;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use App\CustomLibs\CustomFileSystem\CustomFileHandler;

class TemporaryFilesProcessor extends TemporaryFilesHandler
{

    use TemporaryFileUploadingTrait  ;
    protected string $CopiedTempFilesFolderName = "";
    protected string $CopiedTempFilesFolderPath = "";

//    protected array $FoldersToCopy = [];
    protected array $FilesToCopy = [];

    /**
     * @var array like :
     * [ [ "oldPath" => "" , "newPath" => "" ] ]
     */
    protected array $TempFilesToMove = [];


    /**
     * @return string
     */
    public function getCopiedTempFilesFolderPath() : string
    {
        return  $this->getTempFileFolderPath($this->CopiedTempFilesFolderName);
    }

    /**
     * @return $this
     */
    protected function setCopiedTempFilesFolderPath() : self
    {
        $this->CopiedTempFilesFolderPath = $this->getCopiedTempFilesFolderPath();
        $this->FolderExistOrCreate($this->CopiedTempFilesFolderPath);
        return $this;
    }

    /**
     * @param string $CopiedTempFilesFolderName
     * @return $this
     */
    public function setCopiedTempFilesFolderName(string $CopiedTempFilesFolderName): self
    {
        $this->CopiedTempFilesFolderName = $CopiedTempFilesFolderName;
        $this->setCopiedTempFilesFolderPath();
        return $this;
    }

    /**
     * @param array $FoldersToCopy
     * @return $this
     * @throws Exception
     * $FoldersToCopy Must be indexed or associative array like :
     * [
        "Folder1Name" => "Folder1Path" ,
        "Folder2Name" => "Folder2Path" ,
        "Folder3Name" => "Folder3Path"
       ]
     */
//    public function setFoldersToCopy(array $FoldersToCopy): self
//    {
//        foreach ($FoldersToCopy as $FolderName => $FolderPath)
//        {
//            $this->addFolderToCopy( $FolderPath , $FolderName);
//        }
//        return $this;
//    }

    /**
     * @param string|int $folderName
     * @param string $folderPath
     * @return $this
     * @throws Exception
     */
//    public function addFolderToCopy(string $folderPath  , string | int  $folderName = "" ) : self
//    {
//        $this->FolderExistOrFail($folderPath);
//        $folderName = $this->getCopiedFolderDefaultName($folderPath , $folderName);
//        $this->FoldersToCopy[$folderName] = $folderPath;
//        return $this;
//    }

//    protected function getCopiedFolderDefaultName(string $folderPath  , string | int  $folderName = "") : string
//    {
//        return (is_int($folderName) || $folderName == "") ? $this->getFolderDefaultName($folderPath)  : $folderName;
//    }

    /**
     * @param string $fileRelevantPath
     * @param string $folderName
     * @return string
     * @throws Exception
     */
    protected function getFileFolderOrDefaultFolder(string $fileRelevantPath , string  $folderName = "") : string
    {
        if(!$folderName) { $folderName = $this->getFileFolderPath($fileRelevantPath); }
        $this->FolderExistOrCreate($this->getCopiedTempFilesFolderPath() . $folderName) ;
        return $this->processFolderPath($folderName);
    }

    /**
     * @param array $fileInfo
     * @return array|null
     * @throws Exception
     */
    protected function prepareFileInfoArray(array $fileInfo) : array | null
    {
        if(!isset($fileInfo["fileRelevantPath"]) ) { return null;  }
        if(!isset($fileInfo["name"])) { $fileInfo["name"] = "";  }
        if(!isset($fileInfo["FolderName"])){ $fileInfo["FolderName"] = ""; }
        return $fileInfo;
    }

    /**
     * @param array $FilesToCopy
     * @return $this
     * @throws Exception
     * $FilesToCopy Must be like :
     * [
     *  ["name" => $fileName , "path" => $filePath , "FolderName" => $folderName]
     * ]
     */
    public function setTempFilesToCopy(array $FilesToCopy): self
    {
        foreach ($FilesToCopy as $file)
        {
            $file = $this->prepareFileInfoArray($file);
            if($file)
            {
               $this->addTempFileToCopy($file["fileRelevantPath"]  , $file["FolderName"] , $file["name"] );
            }
        }
        return $this;
    }

    /**
     * @param string $fileContent
     * @param string $fileRelevantPath
     * @return $this
     */
    public function HandleTempFileContentToCopy(string $fileContent  , string $fileRelevantPath ) : self
    {
        if($this->IsItAbsolutePath($fileRelevantPath)){$fileRelevantPath = $this->getFolderFileRelativePath($fileRelevantPath);}

        $filePath = $this->getCopiedTempFilesFolderPath() . $fileRelevantPath;

        /** Make Sure That Folder Is Exists Before Creating Any File */
        $this->FileFolderExistOrCreate($filePath);

        File::put($filePath , $fileContent);
        return $this;
    }

    /**
     * @param string $fileTempPath
     * @param string $fileRelevantPath
     * @return $this
     * @throws Exception
     */
    public function HandleTempFileToCopy( string $fileTempPath  , string $fileRelevantPath = "" ) : self
    {
        $this->FileExistOrFail($fileTempPath);

        if(!$fileRelevantPath) { $fileRelevantPath = $this->getFileDefaultName($fileTempPath); }
        /**  Make Sure That Target Folder Is Exists */
        $targetPath = $this->getCopiedTempFilesFolderPath() . $fileRelevantPath;
        $this->FileFolderExistOrCreate($targetPath);

        return $this->addTempFileInfo($fileTempPath , $targetPath);
    }

    protected function addTempFileInfo(string $oldPath , string $newPath) : self
    {
        $this->TempFilesToMove[] = ["oldPath" => $oldPath , "newPath" => $newPath];
        return $this;
    }

    protected function moveTempFiles() : self
    {
        foreach ($this->TempFilesToMove as $file)
        {
            File::move($file["oldPath"] , $file["newPath"]);
        }
        return $this;
    }

    /**
     * @param string $fileRelevantPath
     * @param string $folderName
     * @param string $fileName
     * @param bool $checkFileExisting
     * @return $this
     * @throws Exception
     */
    public function addTempFileToCopy( string $fileRelevantPath , string  $folderName = "" , string $fileName = "" , bool $checkFileExisting = false) : self
    {
        if($this->IsItAbsolutePath($fileRelevantPath)){ $fileRelevantPath = $this->getFolderFileRelativePath($fileRelevantPath); }

        if($checkFileExisting)
        {
            if(!CustomFileHandler::IsFileExists($fileRelevantPath)){return $this;}
        }

        $fileName = $this->getFileDefaultName( $fileRelevantPath  ,  $fileName  );
        $this->FilesToCopy[] = ["name" => $fileName , "fileRelevantOldPath" => $fileRelevantPath , "FolderName" =>  $this->getFileFolderOrDefaultFolder($fileRelevantPath ,$folderName) ];
        return $this;
    }

//    protected function prepareFoldersToCopying() : self
//    {
//        foreach ($this->FoldersToCopy as $folderName => $folderPath)
//        {
//            $this->prepareFolderToCopying($folderPath , $folderName);
//        }
//        return $this;
//    }

//    protected function prepareFolderToCopying( string $folderPath , string  $folderName) : bool
//    {
//        $folderNewPath = $this->getCopiedTempFilesFolderPath() . $folderName;
//        return File::copyDirectory($folderPath , $folderNewPath);
//    }


    /**
     * @return $this
     * @throws FileNotFoundException
     */
    protected function prepareTempsFilesToCopying() : self
    {
        foreach ($this->FilesToCopy as $file)
        {
            $this->prepareTempsFileToCopying($file);
        }
        return $this;
    }

    /**
     * @param array $fileInfo
     * @return $this
     * @throws FileNotFoundException
     */
    protected function prepareTempsFileToCopying(array $fileInfo) : self
    {
        $fileOldPath = $fileInfo["fileRelevantOldPath"];
        $fileNewPath =  $fileInfo["FolderName"] . $fileInfo["name"];

        return $this->HandleTempFileContentToCopy( CustomFileHandler::getFileContent($fileOldPath), $fileNewPath );
    }

    /**
     * @return string
     * Returns CopiedTempFilesFolderPath .... If No CopiedTempFilesFolder  Folder Is Set ... Returns SystemTemporaryFilesMainFolderName
     * So If You Moved An Temp File Without Setting CopiedTempFilesFolderName ... Add Your File Name To
     * Returned SystemTemporaryFilesMainFolderName Path
     * @throws Exception
     */
    public function copyToTempPath() : string
    {
        $this->prepareTempsFilesToCopying()
             ->moveTempFiles()
             ->restartProcessor();

        return $this->getCopiedTempFilesFolderPath();
    }

    /**
     * Restart Processor To Add New Files And Folders (Note : CopiedTempFilesFolderName Still As it set before)
     * @return $this
     */
    protected function restartProcessor() : self
    {
        $this->FilesToCopy = [];
        $this->TempFilesToMove = [];
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function rollbackCopying() : bool
    {
        return File::deleteDirectory($this->getCopiedTempFilesFolderPath());
    }
}
