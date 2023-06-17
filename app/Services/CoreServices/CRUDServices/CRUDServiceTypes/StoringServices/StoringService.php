<?php

namespace App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\JsonException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\Traits\RelationshipsStoringMethods;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\Traits\StoringServiceAbstractMethods;

abstract class StoringService extends DataWriterCRUDService
{
    use  RelationshipsStoringMethods , StoringServiceAbstractMethods;

    protected string $definitionModelClass;
    protected array $fillableColumns = [];

    protected function setModelFillableColumns(): self
    {
        $this->fillableColumns = app($this->definitionModelClass)->getFillable();
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    public function setDefinitionModelClass(): self
    {
        $definitionModelClass = $this->getDefinitionModelClass();
        if (!class_exists($definitionModelClass)) {
            throw new JsonException("The Given Definition Model Class " . $definitionModelClass . " Is Not defined !");
        }
        $this->definitionModelClass = $definitionModelClass;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setDefinitionModelClass()
             ->setModelFillableColumns();
    }

    /**
     * @param array $row
     * @return Model|null
     */
    protected function createDefinitionModelInstance(array $row): Model | null
    {
        return $this->definitionModelClass::make($row);
    }

    /**
     * @param Request|array $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request | array $request): JsonResponse
    {
        try {
            //initValidator Is Used To initialize the all request data validator
            //If Creation Is Multiple .... each row will be validated by single validation operation and specific validation rules
            $this->initValidator($request)->setRequestData()->validateData();

            //If No Exception Is Thrown From Validation Methods .... Database Transaction Will Start
            DB::beginTransaction();

            $this->doBeforeOperation()->createConveniently()->doAfterOperation();
            //If No Exception Is Thrown From Previous Operations ... All Thing Is OK
            //So Database Transaction Will Be Commit
            DB::commit();
            $this->uploadFiles();
            //Response After getting Success
            return Response::success([ ], [$this->getDefinitionCreatingSuccessMessage()]);
        } catch (Exception $e) {
            //When An Exception Is Thrown ....  Database Transaction Will Be Rollback
            DB::rollBack();
            //Response The Error Messages By Exception Messages
            return Response::error([$e->getMessage()]);
        }
    }
}
