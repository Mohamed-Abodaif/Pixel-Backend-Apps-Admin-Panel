<?php

namespace App\Services\WorkSector\ClientsModule\ClientServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\ClientsModule\StoringClientRequest;
use App\Models\WorkSector\ClientsModule\Client;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class ClientStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Client !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Client Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Client::class;
    }

    protected function getRequestClass(): string
    {
        return StoringClientRequest::class;
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
            'name',
            'billing_address',
            'type',
            'registration_no',
            'registration_no_attachment',
            'taxes_no',
            'taxes_no_attachment',
            'exemption_attachment',
            'sales_taxes_attachment',
            'logo',
            'notes'
        ];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name"]);
    }
}
