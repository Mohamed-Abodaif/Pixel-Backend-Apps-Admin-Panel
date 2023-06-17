<?php

namespace App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices;


use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\Traits\RelationshipsUpdatingMethods;
use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

abstract class UpdatingService extends DataWriterCRUDService
{
    use RelationshipsUpdatingMethods;

    abstract protected function getRequestClass() : string;
    abstract protected function getDefinitionUpdatingFailingErrorMessage() : string;
    abstract protected function getDefinitionUpdatingSuccessMessage() : string;

    protected Model $definitionModel ;

    public function __construct(Model $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }

    /**
     * @return DataWriterCRUDService
     * @throws Exception
     */
    protected function updateDefinition() : DataWriterCRUDService
    {
        $this->definitionModel = $this->MakeModelFilesReadyToUpload($this->data , $this->definitionModel);
        $this->definitionModel->update($this->data);
        return $this->HandleModelRelationships($this->data , $this->definitionModel);
    }

    public function update(Request | array $request) : JsonResponse
    {
        try {
            $this->initValidator($request)->setRequestData()->validateData();

            DB::beginTransaction();
            $this->doBeforeOperation()->updateDefinition()->doAfterOperation();

            //If No Exception Is Thrown From Previous Operations ... All Thing Is OK
            //So Database Transaction Will Be Commit
            DB::commit();
            $this->uploadFiles();
            //Response After getting Success
            return Response::success([] , [$this->getDefinitionUpdatingSuccessMessage() ] );
        }catch (Exception $e)
        {
            //When An Exception Is Thrown ....  Database Transaction Will Be Rollback
            DB::rollBack();

            //Response The Error Messages By Exception Messages
            return Response::error( [$e->getMessage()]);
        }
    }

}
