<?php
namespace App\Services\WorkSector\FinancesModule\TaxesAndInsurances\InsuranceExpense;

use App\Http\Requests\WorkSector\ClientsModule\UpdatingPurchaseRequestRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class InsuranceExpenseUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Insurance Expense  !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Insurance Expense  Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingPurchaseRequestRequest::class;
    }
}
        