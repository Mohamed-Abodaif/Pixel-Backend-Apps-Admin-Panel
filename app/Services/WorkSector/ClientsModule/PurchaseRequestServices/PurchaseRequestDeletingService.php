<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class PurchaseRequestDeletingService extends WorkSectorDeletingService
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
