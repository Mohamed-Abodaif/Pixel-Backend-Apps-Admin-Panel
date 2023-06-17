<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentTermsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;


class PaymentTermDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Payment Term";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Payment Term Has Been Deleted Successfully !";
    }
}
