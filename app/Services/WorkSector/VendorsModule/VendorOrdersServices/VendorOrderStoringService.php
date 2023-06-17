<?php

namespace App\Services\WorkSector\VendorsModule\VendorOrderServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\VendorsModule\StoringVendorOrderRequest;
use App\Models\WorkSector\VendorsModule\VendorOrder;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class VendorOrderStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Vendor Order !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Vendor Order Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return VendorOrder::class;
    }

    protected function getRequestClass(): string
    {
        return StoringVendorOrderRequest::class;
    }
}
