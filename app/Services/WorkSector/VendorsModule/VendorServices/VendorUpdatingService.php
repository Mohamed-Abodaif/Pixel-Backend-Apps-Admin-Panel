<?php

namespace App\Services\WorkSector\VendorsModule\VendorServices;


use App\Http\Requests\WorkSector\VendorsModule\UpdatingVendorRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class VendorUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Vendor !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Vendor Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingVendorRequest::class;
    }
}
