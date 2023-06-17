<?php

namespace App\Services\PersonalSector\PersonalTransactions\Outflow\ExchangeExpenseService;

use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\ExchangeExpenseRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class ExchangeExpenseUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given Exchange Expense !';
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return 'The Exchange Expense Has Been Updated Successfully !';
    }

    protected function getRequestClass(): string
    {
        return ExchangeExpenseRequest::class;
    }
}
