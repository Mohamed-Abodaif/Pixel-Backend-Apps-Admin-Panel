<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow\StoringExpenseRequest;
use App\Models\PersonalSector\PersonalTransactions\Outflow\Expense;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class ExpenseStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Expense !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Expense Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Expense::class;
    }

    protected function getRequestClass(): string
    {
        return StoringExpenseRequest::class;
    }
}
