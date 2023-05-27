<?php

namespace App\Services\WorkSector\ClientsModule\ClientQuotationServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\ClientsModule\StoringClientQuotationRequest;
use App\Models\WorkSector\ClientsModule\ClientQuotation;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class ClientQuotationStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Client Quotation !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Client Quotation Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return ClientQuotation::class;
    }

    protected function getRequestClass(): string
    {
        return StoringClientQuotationRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return [
            'date',
            'due_date',
            'client_id',
            'quotation_number',
            'quotation_name',
            'department_id',
            'payments_terms_id',
            'currency_id',
            'quotation_net_value',
            'quotation_attachments',
            'notes'
        ];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["order_name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["order_name"]);
    }
}
