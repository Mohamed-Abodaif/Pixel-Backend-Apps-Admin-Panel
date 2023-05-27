<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\PaymentTermsOperations;

use App\Http\Requests\SystemConfigurationsRequests\PaymentTerms\UpdatingPaymentTermRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

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
