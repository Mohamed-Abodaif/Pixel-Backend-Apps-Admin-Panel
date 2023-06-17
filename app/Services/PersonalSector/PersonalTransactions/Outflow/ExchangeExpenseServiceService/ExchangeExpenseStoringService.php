<?php

namespace App\Services\PersonalSector\PersonalTransactions\Outflow\ExchangeExpenseService;

use App\Models\PersonalSector\PersonalTransactions\Outflow\ExchangeExpense;
use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\ExchangeExpenseRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class ExchangeExpenseStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given ExchangeExpense !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The ExchangeExpense Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return ExchangeExpense::class;
    }

    protected function getRequestClass(): string
    {
        return ExchangeExpenseRequest::class;
    }
}
