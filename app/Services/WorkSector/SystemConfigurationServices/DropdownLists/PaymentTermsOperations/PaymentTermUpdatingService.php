<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentTermsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurations\PaymentTerms\UpdatingPaymentTermRequest;

class PaymentTermUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Payment Term !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Payment Term Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingPaymentTermRequest::class;
    }
}
