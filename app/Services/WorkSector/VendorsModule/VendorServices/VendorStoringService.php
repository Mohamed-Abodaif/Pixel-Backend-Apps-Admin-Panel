<?php

namespace App\Services\WorkSector\VendorsModule\VendorServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\VendorsModule\StoringVendorRequest;
use App\Models\WorkSector\VendorsModule\Vendor;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class VendorStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Vendor !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Vendor Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Vendor::class;
    }

    protected function getRequestClass(): string
    {
        return StoringVendorRequest::class;
    }
}
