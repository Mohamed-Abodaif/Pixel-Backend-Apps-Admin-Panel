<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;


use App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow\UpdatingExpenseRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class ExpenseUpdatingService extends UpdatingService
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
