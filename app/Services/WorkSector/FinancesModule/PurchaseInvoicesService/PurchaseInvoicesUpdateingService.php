<?php
namespace App\Services\WorkSector\FinancesModule\PurchaseInvoices;

use App\Http\Requests\WorkSector\ClientsModule\UpdatingPurchaseRequestRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class PurchaseInvoicesUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Purchase Invoice !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Purchase Invoice Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingPurchaseRequestRequest::class;
    }
}
        