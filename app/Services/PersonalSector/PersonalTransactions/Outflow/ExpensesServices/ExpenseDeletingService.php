<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class ExpenseDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Expense";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Expense Has Been Deleted Successfully !";
    }
}
