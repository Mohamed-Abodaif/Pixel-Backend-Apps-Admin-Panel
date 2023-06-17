<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentMethodsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;


class PaymentMethodDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Payment Method";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Payment Method Has Been Deleted Successfully !";
    }
}
