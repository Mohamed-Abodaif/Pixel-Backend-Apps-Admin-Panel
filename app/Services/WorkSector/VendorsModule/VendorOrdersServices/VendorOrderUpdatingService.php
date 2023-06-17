<?php

namespace App\Services\WorkSector\VendorsModule\VendorOrderServices;


use App\Http\Requests\WorkSector\VendorsModule\UpdatingVendorOrderRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class VendorOrderUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Vendor Order !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Vendor Order Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingVendorOrderRequest::class;
    }
}
