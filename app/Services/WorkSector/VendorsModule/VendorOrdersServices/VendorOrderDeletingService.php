<?php

namespace App\Services\WorkSector\VendorsModule\VendorOrderServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class VendorOrderDeletingService extends WorkSectorDeletingService
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
