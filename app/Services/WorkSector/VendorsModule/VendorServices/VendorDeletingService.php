<?php

namespace App\Services\WorkSector\VendorsModule\VendorServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class VendorDeletingService extends WorkSectorDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Vendor";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Vendor Has Been Deleted Successfully !";
    }
}
