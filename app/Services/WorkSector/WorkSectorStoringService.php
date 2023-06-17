<?php

namespace App\Services\WorkSector;

use App\Exceptions\JsonException;
use App\Models\WorkSector\UsersModule\User;
use App\Services\WorkSector\ValidationOperationsTrait;
use App\Services\WorkSector\CustomisationHooksMethods;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;

abstract class WorkSectorStoringService
{
    use ValidationOperationsTrait, CustomisationHooksMethods , DataArrayProcessorMethods;

    protected string $definitionModelClass;
    protected array $fillableColumns = [];
    protected array $definitionsIDS = [];


    protected function IsItMultipleCreation(): bool
    {
        return $this instanceof MustCreatedMultiplexed;
    }

    //Model And Operation Method
    abstract protected function getDefinitionModelClass(): string;

    //Responding Methods
    abstract protected function getDefinitionCreatingFailingErrorMessage(): string;
    abstract protected function getDefinitionCreatingSuccessMessage(): string;

    //Validation Methods
    abstract protected function getRequestClass(): string;

    protected function setFillableColumns(): self
    {
        $this->fillableColumns = app($this->definitionModelClass)->getFillable();
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function setDefinitionModelClass(): self
    {
        $definitionModelClass = $this->getDefinitionModelClass();
        if (!class_exists($definitionModelClass)) {
            throw new Exception("The Given Definition Model Class " . $definitionModelClass . " Is Not defined !");
        }
        $this->definitionModelClass = $definitionModelClass;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setDefinitionModelClass()->setFillableColumns();
    }

    /**
     * @param array $row
     * @return bool
     * @throws Exception
     */
    protected function validateSingleRow(array $row): bool
    {
        /** @var MustCreatedMultiplexed $this */

        $this->validator->setRequestData($row);
        $validationResult = $this->validator->validate();
        if (is_array($validationResult)) {
            throw new Exception(join(" , ", $validationResult));
        }
        return true;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function createSingleDefinitionModel(array $row): self
    {
        $this->definitionsIDS[] = $this->definitionModelClass::insertGetId($row);
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function createDefinitions(array $data): self
    {
        foreach ($data as $row) {
            $this->validateSingleRow($row);
            $this->createSingleDefinitionModel($row);
        }
        //If No Exception Is Thrown => The Given Rows Are Created
        return $this;
    }

    protected function getCreationDataArray(): array | null
    {
        // if ($this->IsItMultipleCreation()) {
        //     /** @var MustCreatedMultiplexed $this */
        //     return $this->data[$this->getRequestDataKey()] ?? null;
        // }
        return [$this->data];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function makeDataReadyToUse(): array
    {
        $data = $this->getCreationDataArray();
        if ($data === null) {
            throw new JsonException($this->getDefinitionCreatingFailingErrorMessage());
        }
        $dateFields = $this instanceof NeedToStoreDateFields ? $this->getDateFieldNames() : [];
        return $this->sanitizeFillablesValues($data, $dateFields);
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function createDefinitionConveniently(): self
    {
        $data = $this->makeDataReadyToUse();
        if ($this->IsItMultipleCreation()) {
            /** @var MustCreatedMultiplexed $this */

            $this->setSingleRowValidationRules();
            $this->createDefinitions($data);
            return $this;
        }
        $this->createSingleDefinitionModel($data[0]);
        return $this;
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
            $this->initValidator($request)->getRequestData()->validateData();

            //If No Exception Is Thrown From Validation Methods .... Database Transaction Will Start
            DB::beginTransaction();

            $this->doBeforeOperation()->createDefinitionConveniently()->DefinitionRelationshipsHandler()->doAfterOperation();
            //If No Exception Is Thrown From Previous Operations ... All Thing Is OK
            //So Database Transaction Will Be Commit
            DB::commit();
            //Response After getting Success
            return Response::success(["Created_Model_IDS" => $this->definitionsIDS], [$this->getDefinitionCreatingSuccessMessage()]);
        } catch (Exception $e) {
            //When An Exception Is Thrown ....  Database Transaction Will Be Rollback
            DB::rollBack();
            //Response The Error Messages By Exception Messages
            return Response::error([$e->getMessage()]);
        }
    }
}
