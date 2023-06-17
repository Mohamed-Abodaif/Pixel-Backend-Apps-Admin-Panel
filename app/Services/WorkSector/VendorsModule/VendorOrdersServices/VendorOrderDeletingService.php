<?php

namespace App\Services\WorkSector\VendorsModule\VendorOrderServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class VendorOrderDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Vendor Order";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Vendor Order Has Been Deleted Successfully !";
    }
}
