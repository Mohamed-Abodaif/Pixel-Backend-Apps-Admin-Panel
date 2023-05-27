<?php

namespace App\CustomLibs\CustomFileSystem;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use App\CustomLibs\CustomFileSystem\Traits\FileFakingMethods;

abstract class CustomFileUploader
{
    use FileFakingMethods;

    protected array $file_path_uploadedFile_pairs = [];


    protected function checkFilesManipulationConfig() : bool
    {
        return env("THIRD_PARTY_FILES_MANIPULATION") && env("FILESYSTEM_DRIVER") == "s3" && env("APP_ENV") != "local";
    }

    //All definition values getter functions will be called here
    public function __construct()
    {

    }

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

    protected function getFileHashedName(UploadedFile $file , string $folderName) : string
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
     * @param bool $JsonResult
     * @return array
     * @throws Exception
     */
    public function processMultiUploadedFile(Request | array $data , string $fileKey , string $fileFolder  , bool $JsonResult = false ) : array
    {
        $files = $this->getFileFromDataArray($data , $fileKey);
        if(!is_array($files)) { throw new Exception(str_replace("_" , " " , $fileKey ) . " Must Be An Array Of Files");}

        //If File Is really an array of files
        $files_path_array = [];
        foreach ($files as  $file)
        {
            /** @var UploadedFile $file */
            $file_new_path = $this->getFileHashedName($file , $fileFolder);
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
     * @return array
     * @throws Exception
     */
    //Will return the data array after editing it .... Or an Exception if an error is thrown
    public function processFile(Request | array $data , string $fileKey , string $fileFolder ) : array
    {
        //Getting UploadedFile Object From data array
        $uploadedFileOb = $this->getFileFromDataArray($data , $fileKey);

        //Getting File Hashed Name To send it to DB
        //Getting File With Function To check its type ... since InvalidArgumentException object will be thrown if it is not UploadedFile Object
        /**  @var UploadedFile $uploadedFileOb */
        $fileName = $this->getFileHashedName($uploadedFileOb ,  $fileFolder) ;

        //Make File Object ready to save with its path
        $this->makeFileReadyToStore($fileName , $uploadedFileOb);

        //returning data array
        $data[$fileKey] = $fileName;
        return $data;
    }

    /**
     * @param string $folderPath
     * @return bool
     * check if a folder or file is exists in its path (Return Boolean Value Without exception on failing)
     */
    public function IsFolderExist(string $folderPath) : bool
    {
        return Storage::exists($folderPath);
    }

    /**
     * @param string $folderPath
     * @return bool
     * @throws Exception
     * check if a folder or file is exists in its path (Return Boolean Value With exception on failing)
     */
    public function FolderExistOrFail(string $folderPath) : bool
    {
        if( $this->IsFolderExist($folderPath) ){return true;}
        throw new Exception("Folder " . $folderPath . " Not Found !");
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     * check if a folder of the given file is exists in its path
     */
    public function checkFileFolderParentPath(string $filePath) : bool
    {
        return $this->FolderExistOrFail(dirname($filePath));
    }

    /**
     * @param string $file_path
     * @param UploadedFile $uploadedFile
     * @return string
     */
    protected function uploadSingleFile(string $file_path , UploadedFile $uploadedFile) : string
    {
        return $uploadedFile->storeAs("/" , $file_path );
    }


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
            return true;
        }catch (InvalidArgumentException $e)
        {
            return $e;
        }
    }
}
