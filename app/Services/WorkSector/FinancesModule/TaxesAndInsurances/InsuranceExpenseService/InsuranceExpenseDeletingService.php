<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class InsuranceExpenseDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Insurance Expense";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Insurance Expense Has Been Deleted Successfully !";
    }
}
