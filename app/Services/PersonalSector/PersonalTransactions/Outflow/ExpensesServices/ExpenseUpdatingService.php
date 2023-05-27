<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;


use App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow\UpdatingExpenseRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class ExpenseUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Expense !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Expense Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingExpenseRequest::class;
    }
}
