<?php

namespace App\CustomLibs\ExportersManagement\ExportedFilesProcessor;


use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExportedFilesProcessor
{
    protected array $FoldersToExport = [];
    protected array $FilesToExport = [];


    protected string $ExportingMainFolderName = "SystemExportedData";
    protected string $ExportingMainFolderPath = "";
    protected string $ExportedDataVersionName = "";
    protected string $ExportedDataVersionPath = "";

    public function __construct()
    {
        $this->setExportingMainFolderPath();
    }

    /**
     * @param string $ExportedDataVersionName
     * @return $this
     */
    public function setExportedDataVersionName(string $ExportedDataVersionName): self
    {
        $this->ExportedDataVersionName = $ExportedDataVersionName;
        $this->setExportedDataVersionPath();
        return $this;
    }

    protected function processFolderPath(string $folderPath) : string
    {
        return Str::endsWith($folderPath , "/") ? $folderPath : $folderPath . "/";
    }

    public function getExportingMainFolderPath() : string
    {
        return $this->processFolderPath( storage_path("app/public/" . $this->ExportingMainFolderName) );
    }

    public function getExportedDataVersionPath() : string
    {
        return $this->processFolderPath($this->getExportingMainFolderPath()  . $this->ExportedDataVersionName );
    }

    protected function getVersionFolderDownloadingLink() : string
    {
        $path = $this->processFolderPath($this->ExportingMainFolderName ) . $this->ExportedDataVersionName;
        return asset($path);
    }

    protected function setExportingMainFolderPath() : self
    {
        $this->ExportingMainFolderPath =  $this->getExportingMainFolderPath();
        return $this;
    }

    protected function setExportedDataVersionPath() : self
    {
        $this->ExportedDataVersionPath = $this->getExportedDataVersionPath();
        return $this;
    }

    /**
     * @param array $FoldersToExport
     * @return $this
     * @throws Exception
     * $FoldersToExport Must be indexed or associative array like :
     * [
        "Folder1Name" => "Folder1Path" ,
        "Folder2Name" => "Folder2Path" ,
        "Folder3Name" => "Folder3Path"
       ]
     */
    public function setFoldersToExport(array $FoldersToExport): self
    {
        foreach ($FoldersToExport as $FolderName => $FolderPath)
        {
            $this->addFolderToExport( $FolderPath , $FolderName);
        }
        return $this;
    }

    /**
     * @param string|int $folderName
     * @param string $folderPath
     * @return $this
     * @throws Exception
     */
    public function addFolderToExport(string $folderPath , string | int  $folderName = "" ) : self
    {
        $this->FolderExistOrFail($folderPath);
        if(is_int($folderName) || $folderName == ""){ $folderName = File::name($folderPath) ;}
        $this->FoldersToExport[$folderName] = $folderPath;
        return $this;
    }

    /**
     * @param array $FilesToExport
     * @return $this
     * @throws Exception
     * $FilesToExport Must be like :
     * [
     *  ["name" => $fileName , "path" => $filePath , "FolderName" => $folderName]
     * ]
     */
    public function setFilesToExport(array $FilesToExport): self
    {
        foreach ($FilesToExport as $file)
        {
            $file = $this->getValidFileInfoArray($file);
            if(!$file){continue;}
            $this->addFileToExport($file["name"] , $file["path"] , $file["FolderName"]);
        }
        $this->FilesToExport = $FilesToExport;
        return $this;
    }

    /**
     * @param array $fileInfo
     * @return array|null
     * @throws Exception
     */
    protected function getValidFileInfoArray(array $fileInfo) : array | null
    {
        if (isset($fileInfo["path"]) ) { return null;  }

        if(!isset($fileInfo["name"])) { $fileInfo["name"] = $this->getFileDefaultName($fileInfo["path"]);  }

        if(!isset($fileInfo["FolderName"])){ $fileInfo["FolderName"] = $this->getFileFolderOrDefaultFolder(); }
        return $fileInfo;
    }

    /**
     * @param string $folderName
     * @return string
     * @throws Exception
     */
    protected function getFileFolderOrDefaultFolder(string  $folderName = "") : string
    {
        //need To fix
        if($folderName != "" ){return $folderName;}
        $this->checkExportedDataVersionName();
        return $this->ExportedDataVersionName;
    }

    protected function getFileDefaultName(string $filePath  , string $fileName = "") : string
    {
        if($fileName != "" ){return $fileName;}
        return File::basename($filePath);
    }

    /**
     * @param string $fileName
     * @param string $filePath
     * @param string $folderName
     * @return $this
     * @throws Exception
     */
    public function addFileToExport( string $filePath , string  $folderName = "" , string $fileName = "") : self
    {
        $this->FileExistOrFail($filePath);
        $fileName = $this->getFileDefaultName( $filePath  ,  $fileName  );
        $this->FilesToExport[] = ["name" => $fileName , "path" => $filePath , "FolderName" =>  $this->getFileFolderOrDefaultFolder($folderName) ];
        return $this;
    }

    /**
     * @param string $filePath
     * @return $this
     * @throws Exception
     */
    protected function FolderExistOrFail(string $filePath  ) : self
    {
        if(File::exists($filePath)){return $this;}
        throw new Exception("The Given Folder Is Not Exists In The Given Path");
    }

    /**
     * @param string $filePath
     * @return $this
     * @throws Exception
     */
    protected function FileExistOrFail(string $filePath ) : self
    {
        if(File::exists($filePath)){return $this;}
        throw new Exception("The Given File Is Not Exists In The Given Path");
    }

    protected function prepareFoldersToExporting() : self
    {
        foreach ($this->FoldersToExport as $folderName => $folderPath)
        {
            $this->prepareFolderToExporting($folderPath , $folderName);
        }
        return $this;
    }

    protected function prepareFolderToExporting( string $folderPath , string  $folderName) : bool
    {
        $folderNewPath = $this->getExportedDataVersionPath() . $folderName;
        return File::copyDirectory($folderPath , $folderNewPath);
    }

    protected function prepareFilesToExporting() : self
    {
        foreach ($this->FilesToExport as $file)
        {
            $this->prepareFileToExporting($file);
        }
        return $this;
    }

    protected function prepareFileToExporting(array $fileInfo) : bool
    {
        $fileName = $fileInfo["name"];
        $fileOldPath = $fileInfo["path"];
        $fileFolderName = $fileInfo["FolderName"];
        $fileNewPath = $this->ExportedDataVersionPath . $this->processFolderPath($fileFolderName) . $fileName;
        return File::copy($fileOldPath , $fileNewPath);
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function checkExportedDataVersionName() : self
    {
       if($this->ExportedDataVersionName == ""){throw new Exception("Exported Data Version Name Is Not Set !");}
       return $this;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function checkNeededOperations() : self
    {
        return $this->checkExportedDataVersionName();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function export() : string
    {
        $this->checkNeededOperations()->prepareFoldersToExporting()->prepareFilesToExporting();
        return $this->getVersionFolderDownloadingLink();
    }
}
