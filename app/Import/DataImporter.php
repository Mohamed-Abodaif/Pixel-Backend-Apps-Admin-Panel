<?php

namespace App\Import;

use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\ImportFileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Exception;
use Illuminate\Support\Collection;

abstract class DataImporter
{
    protected Validator $validator;
    protected string $modelClass ;
    protected array $RequestData = [];
    protected Collection | array $fileData ;
    protected FastExcel $fastExcel;


    protected string $filePath ;

    protected function getFileRequestKey() : string
    {
        return "file";
    }

    abstract protected function getModelClass() : string;
    abstract protected function getRequestGeneralRequestForm() : string;
    abstract protected function getRequestGeneralRequest() : string;
    /**
     * @throws Exception
     */
    function __construct()
    {
        $this->modelClass = $this->getModelClass();
        $this->cb = fn () => throw new Exception("There Is No Map Function To implement");
    }

    /**
     * @param string $modelClass
     * @return $this
     * @throws Exception
     */
    public function changeModelClass(string $modelClass): self
    {
        if (!class_exists($modelClass)){throw new Exception("The Given Model Class Is Not Defined !"); }
        $this->modelClass = $modelClass;
        return $this;
    }

    /**
     * @param callable $cb
     * @return $this
     */
    public function map(callable $cb) : self
    {
        $this->cb = $cb;
        return $this;
    }

    /**
     * @return string|$this
     * @throws Exception
     */
    public function setFilePath() : self | string
    {
        /** @var UploadedFile $file
         * We Don't Check if File Is Exists Because That Done In Request Validation
         * But File Value Maybe string ... so we check the value type
         */
        $file = $this->RequestData[$this->getFileRequestKey()];
        if(!$file instanceof UploadedFile){ throw new Exception("There Is No File Is Uploaded ! ") ; }

        //Getting File's Temporary Path ... There Is No Need To Upload It Anywhere
        $this->filePath = $file->getRealPath();
        return $this;
    }

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    protected function initGeneralValidator(Request | array $request) : self
    {
        $this->validator = new ArrayValidator(ImportFileRequest::class , $request);
        return $this;
    }

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    protected function validateRequest(Request | array $request) : self
    {
        $this->initGeneralValidator($request);
        $validationResult = $this->validator->validate();
        if(is_array($validationResult)){throw new Exception( join(" , " , $validationResult) );}
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return $this
     */
    protected function initFastExcel() : self
    {
        $this->fastExcel = new FastExcel();
        return $this;
    }

    /**
     * @return $this
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    protected function getFileData() : self
    {
        $this->initFastExcel();
        $this->fileData =  $this->fastExcel->import($this->filePath, $this->cb);
        return $this;
    }

    protected function importSingleRow()
    {

    }
    protected function vaidateSingleRow()
    {

    }
    public function import(Request | array $request)
    {
        try {
            $this->validateRequest($request)->setFilePath()->getFileData();
            return Response::success([] , ["File's Data Has Been Imported Successfully"]);
        }catch (Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }
}
