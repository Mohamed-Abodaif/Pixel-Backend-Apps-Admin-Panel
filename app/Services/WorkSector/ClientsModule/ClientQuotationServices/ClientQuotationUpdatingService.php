<?php

namespace App\Services\WorkSector\ClientsModule\ClientQuotationServices;


use App\Http\Requests\WorkSector\ClientsModule\UpdatingClientQuotationRequest ;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class ClientQuotationUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Client Quotation !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Client Quotation Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingClientQuotationRequest::class;
    }
}
