<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class ExpenseDeletingService extends WorkSectorDeletingService
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
