<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class PurchaseInvoicesDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Purchase Invoice";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Purchase Invoice Has Been Deleted Successfully !";
    }
}
