<?php

namespace App\Services\WorkSector\ClientsModule\ClientQuotationServices;

use App\Http\Requests\WorkSector\ClientsModule\StoringClientQuotationRequest;
use App\Models\WorkSector\ClientsModule\ClientQuotation;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class ClientQuotationStoringService extends SingleRowStoringService
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
}
