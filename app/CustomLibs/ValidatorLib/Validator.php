<?php

namespace App\CustomLibs\ValidatorLib;


use App\CustomLibs\ValidatorLib\Traits\ValidationRulesHandler;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Contracts\Validation\Validator as ValidationResultOb ;


abstract class Validator
{
    use ValidationRulesHandler;

    protected array $data = [];
    protected BaseFormRequest $requestFormOb ;

    /**
     * @param string $requestFormClass
     * @param Request|array $request
     * @throws Exception
     */
    public function __construct(string $requestFormClass , Request | array  $request )
    {
        $this->setRequestData($request);
        $this->changeRequestClass($requestFormClass);
    }

    /**
     * @param string $requestFormClass
     * @return $this
     * @throws Exception
     */
    public function changeRequestClass(string $requestFormClass ) : self
    {
        if(!class_exists($requestFormClass)){throw new Exception("The Given Request Class Is Invalid Class !");}
        $requestForm = new $requestFormClass();
        if(!$requestForm instanceof BaseFormRequest){throw new Exception("The Given Request Class Is Invalid Request  Form Class !");}
        $this->requestFormOb = $requestForm;
        return $this;
    }


    /**
     * @param array|Request $data
     * @return $this
     */
    public function setRequestData(array | Request $data) : self
    {
        if($data instanceof Request){$data = $data->all(); }
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestData() : array
    {
        return $this->data;
    }

    /**
     * @return ValidationResultOb|null
     * @throws Exception
     */
    protected function getValidationResultOb() : ValidationResultOb | null
    {
        if($this->setDefaultRules){$this->AllRules();}
        if(empty($this->allRules)){return null;}
        $validationMessages = $this->requestFormOb->messages();
        return ValidatorFacade::make($this->data , $this->allRules , $validationMessages);
    }

    public function validate(): array | bool | JsonResponse | RedirectResponse
    {
        try {
            return $this->validateResponder($this->getValidationResultOb());
        }catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    protected function validateResponder(?ValidationResultOb $validatorResultOb = null): array | bool | JsonResponse | RedirectResponse
    {
        //This happens when no validation is required (when rules Array is empty = the validation operation is done)
        if($validatorResultOb == null){ return true; }

        //this happen
        if($validatorResultOb->fails()) { return $this->ErrorResponder($validatorResultOb); }

        //Validation is done
        return true;
    }

    abstract protected function ErrorResponder(ValidationResultOb $validatorResultOb) : array | bool | JsonResponse | RedirectResponse;

}
