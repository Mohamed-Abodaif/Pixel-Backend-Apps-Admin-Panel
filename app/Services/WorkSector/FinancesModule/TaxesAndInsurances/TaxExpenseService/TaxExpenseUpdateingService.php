<?php

namespace App\Services\WorkSector\FinancesModule\TaxesAndInsurances\TaxExpenseService;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class TaxExpenseUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given TaxExpense !';
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return 'The TaxExpense Has Been Created Successfully !';
    }

    protected function getRequestClass(): string
    {
        return TaxExpenseRequest::class;
    }
}
