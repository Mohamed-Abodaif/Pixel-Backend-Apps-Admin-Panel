<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class PurchaseRequestDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Purchase Request";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Purchase Request Has Been Deleted Successfully !";
    }
}
