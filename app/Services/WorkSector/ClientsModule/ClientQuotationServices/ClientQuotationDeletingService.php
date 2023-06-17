<?php

namespace App\Services\WorkSector\ClientsModule\ClientQuotationServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class ClientQuotationDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Client Quotation";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Client Quotation Has Been Deleted Successfully !";
    }
}
