<?php

namespace App\Services\WorkSector\SystemConfigurationServices;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

abstract class SystemConfigurationDeletingService extends SystemConfigurationManagementService
{
    use CustomisationHooksMethods;

    abstract protected function getDefinitionDeletingFailingErrorMessage() : string;
    abstract protected function getDefinitionDeletingSuccessMessage() : string;

    protected Model $definitionModel ;

    public function __construct(Model $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }


    /**
     * @return $this
     * @throws Exception
     */
    protected function deleteSoftly() : self
    {
        if(!$this->definitionModel->delete()){throw new Exception($this->getDefinitionDeletingFailingErrorMessage());}
        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function forcedDelete()
    {
        if(!$this->definitionModel->forceDelete()){throw new Exception($this->getDefinitionDeletingFailingErrorMessage());}
    }

    /**
     * @param bool $forcedDeleting
     * @return $this
     * @throws Exception
     */
    protected function DeleteWithConvenientDeletingType(bool $forcedDeleting = false) : self
    {
        if($forcedDeleting)
        {
            $this->forcedDelete();
            return $this;
        }
        $this->deleteSoftly();
        return $this;
    }


    /**
     * @param bool $forcedDeleting
     * @return JsonResponse
     */
    public function delete(bool $forcedDeleting = false) : JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->doBeforeOperation()
                 ->DefinitionRelationshipsHandler()
                 ->DeleteWithConvenientDeletingType($forcedDeleting)
                 ->doAfterOperation();

            //If No Exception Is Thrown From Previous Operations ... All Thing Is OK
            //So Database Transaction Will Be Commit
            DB::commit();

            //Response After getting Success
            return Response::success([$this->getDefinitionDeletingSuccessMessage()]);

        }catch (Exception $e)
        {
            //When An Exception Is Thrown ....  Database Transaction Will Be Rollback
            DB::rollBack();

            //Response The Error Messages By Exception Messages
            return Response::error([$e->getMessage()]);
        }
    }

}