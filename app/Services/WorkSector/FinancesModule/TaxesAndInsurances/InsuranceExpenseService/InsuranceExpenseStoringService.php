<?php
        namespace App\Services\WorkSector\FinancesModule\TaxesAndInsurances\InsuranceExpense;

use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\InsuranceExpenseRequest;
use App\Models\WorkSector\FinanceModule\TaxesAndInsurances\InsuranceExpense;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class InsuranceExpenseStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given Insurance Expense !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The Insurance Expense Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return InsuranceExpense::class;
    }

    protected function getRequestClass(): string
    {
        return InsuranceExpenseRequest::class;
    }

}

        