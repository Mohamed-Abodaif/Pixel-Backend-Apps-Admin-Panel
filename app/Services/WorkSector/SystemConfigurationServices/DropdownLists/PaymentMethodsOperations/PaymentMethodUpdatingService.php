<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentMethodsOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\PaymentMethods\UpdatingPaymentMethodRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class PaymentMethodUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Payment Method !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Payment Method Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingPaymentMethodRequest::class;
    }
}
