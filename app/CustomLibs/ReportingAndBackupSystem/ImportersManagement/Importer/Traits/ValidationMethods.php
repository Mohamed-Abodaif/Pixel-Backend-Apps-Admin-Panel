<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\CustomLibs\ValidatorLib\Validator;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

trait ValidationMethods
{

    protected ?Validator $validator = null;
    protected string $DataValidationRequestForm;

    abstract protected function getDataValidationRequestForm() : string;
    abstract protected function getModelMultiRowInsertionRules() : array;
    abstract protected function getModelSingleRowInsertionRules() : array;

    public function __construct()
    {

    }

    /**
     * @return ValidationMethods|Importer
     * @throws JsonException
     */
    public function setDataValidationRequestForm(): self
    {
        $DataValidationRequestForm = $this->getDataValidationRequestForm();
        if(! class_exists($DataValidationRequestForm)){throw new JsonException("The Given DataValidationRequestForm Is Not A valid Class Or Not Found !");}
        if(! (new $DataValidationRequestForm()) instanceof FormRequest){throw new JsonException("The Given DataValidationRequestForm Is Not A Request Form Class !"); }
        $this->DataValidationRequestForm = $DataValidationRequestForm;
        return $this;
    }

    /**
     * @param array|Request $request
     * @return Importer
     * @throws Exception
     */
    protected function initValidator(array | Request $request):Importer
    {
        if($this->validator){return $this;}
        $this->validator = new ArrayValidator( $this->DataValidationRequestForm , $request );
        return $this;
    }

    /**
     * @param array $rules
     * @return Importer
     * @throws Exception
     */
    protected function setValidationRulesOrDefaultRules(array $rules = []) : Importer
    {
        if(!empty($rules))
        {
            $this->validator->OnlyRules( $rules );
            return $this;
        }
        $this->validator->AllRules();
        return $this;
    }

    /**
     * @return Importer
     * @throws Exception
     */
    protected function setMultiRowInsertionRules() : Importer
    {
        return $this->setValidationRulesOrDefaultRules(
                    $this->getMultiRowInsertionRules()
                );
    }

    /**
     * @return Importer
     * @throws Exception
     */
    protected function setSingleRowInsertionRules() : Importer
    {
        return $this->setValidationRulesOrDefaultRules(
                    $this->getSingleRowInsertionRules()
                );
    }

    /**
     * @return Importer
     * @throws Exception
     */
    protected function validateFileData() : Importer
    {
        return $this->initValidator($this->ImportedDataArray)->setMultiRowInsertionRules()->validOrFail();
    }

    /**
     * @param array $row
     * @return bool
     * @throws JsonException | Exception
     */
    protected function validateDataRow(array $row) : bool
    {
        $this->validator->setRequestData($row);
        $this->setSingleRowInsertionRules();
        return $this->IsValid();
    }

    /**
     * @return ValidationMethods|Importer
     * @throws JsonException
     */
    protected function validOrFail() :self
    {
        $validationResult = $this->validator->validate();
        if(is_array($validationResult)){ throw new JsonException( join(" , " , $validationResult) );}
        return $this;
    }

    /**
     * @return bool
     */
    protected function IsValid() :bool
    {
        $validationResult = $this->validator->validate();

        /**  If $validationResult Is Not Array .... The Checked Data Is Valid */
        return !is_array($validationResult);
    }

}
