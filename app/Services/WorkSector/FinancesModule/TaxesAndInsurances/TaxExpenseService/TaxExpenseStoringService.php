<?php

namespace App\Services\WorkSector\FinancesModule\TaxesAndInsurances\TaxExpense;

use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\TaxExpenseRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
use App\Models\WorkSector\FinanceModule\TaxesAndInsurances\TaxExpense as TaxesAndInsurancesTaxExpense;

class TaxExpenseStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given TaxExpense !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The TaxExpense Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return TaxesAndInsurancesTaxExpense::class;
    }

    protected function getRequestClass(): string
    {
        return TaxExpenseRequest::class;
    }
}
