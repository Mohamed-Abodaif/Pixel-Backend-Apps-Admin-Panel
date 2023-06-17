<?php

namespace App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices;

use App\Exceptions\JsonException;
use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use Exception;
use Illuminate\Http\Request;

abstract class MultiRowStoringService extends StoringService
{
    /**
     * To get Request Data Array Key ... EX : "items" key will contain the data those required to store
     * Override it from child class if it there is need
    */
    protected function getRequestDataKey(): string
    {
        return "items";
    }

    protected function initValidator(Request $request): StoringService
    {
        parent::initValidator($request);
        return $this->setRequestGeneralValidationRules();
    }

    protected function setRequestGeneralValidationRules(): StoringService
    {
        $this->validator->ExceptRules($this->fillableColumns);
        return $this;
    }

    /**
     * @return StoringService
     * @throws Exception
     */
    protected  function setSingleRowValidationRules(): StoringService
    {
        $this->validator->OnlyRules($this->fillableColumns);
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     * @throws JsonException
     */
    protected function validateSingleRow(array $row): StoringService
    {
        $this->validator->setRequestData($row);
        $validationResult = $this->validator->validate();
        if (is_array($validationResult)) { throw new JsonException(join(" , ", $validationResult)); }
        return $this;
    }

    /**
     * @param array $data
     * @return DataWriterCRUDService
     * @throws JsonException
     * @throws Exception
     */
    protected function createDefinitions(array $data): DataWriterCRUDService
    {
        foreach ($data as $row)
        {
            $modelInstance = $this->validateSingleRow($row)->createDefinitionModelInstance($row);
            /**
             * Make Files Ready To Upload And Setting Files Names Into Model's File path's props
             */
            $model = $this->MakeModelFilesReadyToUpload( $row ,  $modelInstance);

            /**Saving Model Instance To Database After Setting All Fillables Values And Changing Files 's UploadedFile Object's value To The New Path Of File*/
            if($model->save())
            {
                $this->HandleModelRelationships($row , $model);
            }
        }
        //If No Exception Is Thrown => The Given Rows Are Created
        return $this;
    }

    protected function getCreationDataArray(): array | null
    {
        return $this->data[$this->getRequestDataKey()] ?? null;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function makeDataReadyToUse(): array
    {
        $data = $this->getCreationDataArray();
        if (!$data) { throw new JsonException($this->getDefinitionCreatingFailingErrorMessage()); }
        return $data;
    }


    /**
     * @return $this
     * @throws Exception
     */
    protected function createConveniently(): self
    {
        $data = $this->makeDataReadyToUse();
        $this->setSingleRowValidationRules();
        $this->createDefinitions($data);
        return $this;
    }

}
