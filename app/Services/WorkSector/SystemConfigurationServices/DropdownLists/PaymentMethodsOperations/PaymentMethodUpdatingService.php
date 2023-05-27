<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\PaymentMethodsOperations;


use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\SystemConfigurationsRequests\PaymentMethods\UpdatingPaymentMethodRequest;

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
