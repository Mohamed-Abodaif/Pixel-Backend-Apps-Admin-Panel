<?php

namespace App\Services\WorkSector;

use App\Services\WorkSector\CustomisationHooksMethods;
use App\Services\WorkSector\ValidationOperationsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

abstract class WorkSectorUpdatingService 
{
    use CustomisationHooksMethods , ValidationOperationsTrait;

    abstract protected function getRequestClass() : string;
    abstract protected function getDefinitionUpdatingFailingErrorMessage() : string;
    abstract protected function getDefinitionUpdatingSuccessMessage() : string;

    protected Model $definitionModel ;

    public function __construct(Model $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function updateDefinition() : self
    {
        if($this->definitionModel->update($this->data)){return  $this;}
        throw new Exception($this->getDefinitionUpdatingFailingErrorMessage());
    }

    public function update(Request | array $request) : JsonResponse
    {
        try {
            $this->initValidator($request)->getRequestData()->validateData();

            DB::beginTransaction();
            $this->doBeforeOperation()->updateDefinition()->DefinitionRelationshipsHandler()->doAfterOperation();

            //If No Exception Is Thrown From Previous Operations ... All Thing Is OK
            //So Database Transaction Will Be Commit
            DB::commit();

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