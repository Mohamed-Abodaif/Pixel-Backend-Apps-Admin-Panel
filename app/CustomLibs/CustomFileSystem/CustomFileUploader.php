<?php

namespace App\CustomLibs\CustomFileSystem;

use App\Exceptions\JsonException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use App\CustomLibs\CustomFileSystem\Traits\FileFakingMethods;

abstract class CustomFileUploader extends CustomFileHandler
{
    use FileFakingMethods;

    protected array $file_path_uploadedFile_pairs = [];

    /**
     * @param Request|array $data
     * @return array
     */
    protected function getRequestData(Request | array $data) : array
    {
        return $data instanceof Request ? $data->all() : $data;
    }

    /**
     * @param Request|array $data
     * @param string $fileKey
     * @return array|UploadedFile
     * @throws Exception
     */
    protected function getFileFromDataArray(Request | array $data , string $fileKey) : array | UploadedFile
    {
        $data = $this->getRequestData($data);
        if(!isset($data[$fileKey])){ throw  new Exception($fileKey . "'s File Key Is Not Found In The Data Array");}
        return $data[$fileKey];
    }

    public function MustItUpdateFileOldName(UploadedFile $file , string $fileOldName ) : bool
    {
        return $file->getClientOriginalExtension() !== File::extension($fileOldName) ;
    }

    public function getFileHashName(UploadedFile $file , string $folderName = "") : string
    {
        return $file->hashName($folderName);
    }

    //Make File Object ready to save with its path
    // (It can be used to make files ready to upload when file path is set .... use it for updating operation)
    public function makeFileReadyToStore(string $filePath , UploadedFile $file) : bool
    {
        $this->file_path_uploadedFile_pairs[$filePath] = $file;
        return true;
    }

    /**
     * @param Request|array $data
     * @param string $fileKey
     * @param string $fileFolder
     * @param string $filePath
     * @param bool $JsonResult
     * @return array
     * @throws Exception
     */
    public function processMultiUploadedFile(Request | array $data , string $fileKey , string $fileFolder , string $filePath = ""  , bool $JsonResult = false ) : array
    {
        $files = $this->getFileFromDataArray($data , $fileKey);
        if(!is_array($files)) { throw new Exception(str_replace("_" , " " , $fileKey ) . " Must Be An Array Of Files");}

        //If File Is really an array of files
        $files_path_array = [];
        foreach ($files as  $file)
        {
            /** @var UploadedFile $file */
            $file_new_path = $filePath ?? $this->getFileHashName($file , $fileFolder);
            $files_path_array[] = $file_new_path;

            //Make Object ready to save with its path
            $this->makeFileReadyToStore($file_new_path , $file);
        }
        if($JsonResult){$files_path_array = json_encode($files_path_array);}
        $data[$fileKey] = $files_path_array;
        return $data;
    }

    /**
     * @param Request|array $data
     * @param string $fileKey
     * @param string $fileFolder
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    //Will return the data array after editing it .... Or an Exception if an error is thrown
    public function processFile(Request | array $data , string $fileKey , string $fileFolder , string $filePath = "" ) : array
    {
        //Getting UploadedFile Object From data array
        $uploadedFileOb = $this->getFileFromDataArray($data , $fileKey);

        //Getting File Hashed Name To send it to DB
        //Getting File With Function To check its type ... since InvalidArgumentException object will be thrown if it is not UploadedFile Object
        /**  @var UploadedFile $uploadedFileOb */
        $file_new_path = $filePath ?? $this->getFileHashName($uploadedFileOb , $fileFolder);

        //Make File Object ready to save with its path
        $this->makeFileReadyToStore($file_new_path , $uploadedFileOb);

        //returning data array
        $data[$fileKey] = $file_new_path;
        return $data;
    }


    /**
     * @param string $file_path
     * @param UploadedFile|null $uploadedFile
     * @return string
     * @throws JsonException
     */
    protected function uploadSingleFile(string $file_path , ?UploadedFile $uploadedFile = null) : string
    {
        if(!$uploadedFile){throw new JsonException("Expected UploadedFile Object For '$uploadedFile' Parameter , null given !.");}
        return $uploadedFile->storeAs("/" , $file_path , $this->disk);
    }


    protected function restartUploader() : bool
    {
        $this->file_path_uploadedFile_pairs = [];
        return true;
    }

    /**
     * @param array $file_path_uploadedFile_pairs
     * @return bool|Exception
     * @throws JsonException
     */
    public function uploadFiles(array $file_path_uploadedFile_pairs = []) : bool | Exception
    {
        try {
            //If Config Values Is Not True .... Nothing Will Be Uploaded
            if($this->checkFilesManipulationConfig()){return true;}
            if(empty($file_path_uploadedFile_pairs)){$file_path_uploadedFile_pairs = $this->file_path_uploadedFile_pairs;}
            foreach ($file_path_uploadedFile_pairs as $file_path => $uploadedFile)
            {
                $this->uploadSingleFile($file_path , $uploadedFile);
            }
            //No matter if the $file_path_uploadedFile_pairs is empty or not .... if the execution come to this line the function get successful
            return $this->restartUploader();
        }catch (InvalidArgumentException $e)
        {
            return $e;
        }
    }
}
